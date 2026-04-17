<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Private Board</title>
    <style>
        body { font-family: monospace; background-color: #f4f4f4; color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 30px; border: 1px solid #ccc; width: 100%; max-width: 300px; box-shadow: 2px 2px 0px #000; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #999; box-sizing: border-box; font-family: monospace; }
        button { width: 100%; padding: 10px; background: #000; color: #fff; border: none; cursor: pointer; font-family: monospace; font-weight: bold; }
        button:hover { background: #333; }
        .error { color: red; font-size: 0.9em; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>[ ACCESS_PORTAL ]</h2>

    @if($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <label>Username:</label>
        <input type="text" name="name" required autofocus autocomplete="off">

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">LOGIN</button>
    </form>
</div>

</body>
</html>
