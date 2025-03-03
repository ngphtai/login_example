<!DOCTYPE html>
<html lange="en">

<head>
    <meta charset="UTF-8">
    <meta name="Search" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel = "stylesheet" href= "{{ asset('css/style.css') }}">
</head>

<body>
    <div class ="container">
        <form method="GET" action="{{ route('products.search') }}">
            <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Nhập từ khóa...">
            <button type="submit">Search</button>
        </form>
        <br>
        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <h2>Kết quả tìm kiếm cho: {{ $keyword }}</h2>
        <ul>
            @if($products !== null)
                @foreach($products as $product)
                    <li>
                            {{$product->name}}  - {{ number_format($product->price) }} VND
                        <form action="{{ route('products.update', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="text" name="name" value="{{ $product->name }}">
                            <input type="number" name="price" value="{{ $product->price }}">
                            <button type="submit">Cập nhật</button>
                        </form>
                    </li>
                @endforeach
            @else
                <h3>Không tìm thấy sản phẩm</h3>
            @endif
        </ul>
    </div>
</body>

</html>
