<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        @if(session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif
        <form action="{{ route('registerAccount') }}" method="POST">
            @csrf
            <label>Use name:</label>
            <input type="name" name="name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
