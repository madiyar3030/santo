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
        Чтобы изменить пароль перейдите <a href="{{route('password.resetPage', ['token' => $user->access_token])}}">сюда</a>
        <br>
    </p>
    <hr>
    С уважением,<br>
    {{ config('app.name') }}
</div>
</body>
</html>
