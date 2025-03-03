<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Models\Product;
use Exception;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $keyword = strtolower($request->input('keyword', ''));
        $cacheKey = 'search_products_' . md5($keyword);

        try {
            $products = Cache::remember($cacheKey, 3600, function () use ($keyword) {
                if (empty($keyword)) {
                    $allProducts = Product::all();
                    return $allProducts instanceof \Illuminate\Support\Collection ? $allProducts : collect();
                }

                $productIds = Redis::smembers("search:index:{$keyword}") ?: [];

                if (empty($productIds)) {
                    $products = Product::where('name', 'like', "%{$keyword}%")
                        ->get();
                    
                    if ($products->isNotEmpty()) {
                        foreach ($products as $product) {
                            $words = explode(' ', strtolower($product->name));
                            foreach ($words as $word) {
                                if (str_contains($word, $keyword)) {
                                    Redis::sadd("search:index:{$word}", $product->id);
                                }
                            }
                        }
                    }
                    return $products instanceof \Illuminate\Support\Collection ? $products : collect();
                }

                $filteredProducts = Product::whereIn('id', $productIds)
                    ->orderBy('name', 'asc')
                    ->get(['id', 'name', 'price']);
                return $filteredProducts instanceof \Illuminate\Support\Collection ? $filteredProducts : collect();
            });
        } catch (Exception $e) {
            Log::error("Search error: " . $e->getMessage());
            $products = collect(); 
        }
        Log::info($products);
        return view('searchpage', compact('products','keyword'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */

    public function index()
    {
        return view('searchpage');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response | \Illuminate\Contracts\View\View
     */
    public function create(CreateProductRequest $request)
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(CreateProductRequest $request)
    {
        try {
            $request->validated();
            Product::create($request->all());
            return redirect()->refresh()->with('success', 'created successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response | \Illuminate\Contracts\View\View
     */
    public function show(Product $product)
    {
        return view('product.show', compact('product'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response| \Illuminate\Contracts\View\View
     */
    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update(attributes: $request->only('name', 'price'));

        // Xóa cache liên quan đến tìm kiếm (dùng tag hoặc pattern)
        Cache::tags('search')->flush();

        return redirect()->route('products.search')->with('success', 'Cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();
        
        Cache::tags('search')->flush();

        return redirect()->route('products.search')->with('success', 'Xóa thành công!');
    }
}