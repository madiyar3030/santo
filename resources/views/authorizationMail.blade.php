<!DOCTYPE html>
<head>
    <title>Laravel</title>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
    </div>
        <h1>Здраствуйте, {{$user->name}}</h1>
    <p>
        Чтобы подтвердить электронную почту перейдите <a href="{{route('user.verify', ['token' => $user->access_token])}}">сюда</a>
        <br>
    </p>
    <hr>
    С уважением,<br>
    {{ config('app.name') }}
</div>
</body>
</html>
