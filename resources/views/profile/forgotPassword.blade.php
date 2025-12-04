<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot password form</title>

</head>
<body>

    @session('status')
        <div style="color: green; font-weight: bold;">
            {{ session('status') }}
        </div>
    @endsession

    @session('error')
        <div style="color: red; font-weight: bold;">
            {{ session('error') }}
        </div>
    @endsession

    <h1>Forgot Password</h1>
    <form method="POST" action="/forgotPassword">
        @csrf

        <div>
            <label for="email">Email Address:</label>
            <input id="email" type="email" name="email" required autofocus>
        </div>

        <div>
            <button type="submit">
                Send Password Reset Link
            </button>
        </div>
    </form>

</body>
</html>