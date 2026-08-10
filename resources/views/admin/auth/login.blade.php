<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Admin Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .login-container {
            width: 400px;
            max-width: 90%;
            margin: 100px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin-top: 6px;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <h2>Admin Login</h2>

        @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.login.submit') }}">

            @csrf

            <label>Email</label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required>

            <label>Password</label>

            <input
                type="password"
                name="password"
                required>

            <label>
                <input
                    type="checkbox"
                    name="remember"
                    value="1">

                Remember me
            </label>

            <button type="submit">
                Login
            </button>

        </form>

    </div>

</body>

</html>