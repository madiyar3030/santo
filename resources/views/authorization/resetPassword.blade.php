<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Войти | Santo Админ-панель</title>
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{asset('admin-vendor/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{asset('admin-vendor/plugins/node-waves/waves.css')}}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{asset('admin-vendor/plugins/animate-css/animate.css')}}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{asset('admin-vendor/css/style.css')}}" rel="stylesheet">
</head>

<body class="login-page">
<div class="login-box">
    <div class="logo">
        <a href="javascript:void(0);"><b>Santo</b></a>
    </div>
    <div class="card">
        <div class="body">
            @if (!isset($success))
            <form id="reset_password" method="POST" action="{{route('password.reset')}}">
                {{csrf_field()}}
                <div class="msg">Введите новый пароль:</div>
                    <input type="hidden" name="token" value="{{$token ?? ''}}">
                    <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="new_password" placeholder="Пароль" required/>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">Изменить</button>
                        </div>
                    </div>
            </form>
            @else
                <p>{{$success}}</p>
            @endif
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{asset('admin-vendor/plugins/jquery/jquery.min.js')}}"></script>

<!-- Bootstrap Core Js -->
<script src="{{asset('admin-vendor/plugins/bootstrap/js/bootstrap.js')}}"></script>

<!-- Waves Effect Plugin Js -->
<script src="{{asset('admin-vendor/plugins/node-waves/waves.js')}}"></script>

<!-- Validation Plugin Js -->
<script src="{{asset('admin-vendor/plugins/jquery-validation/jquery.validate.js')}}"></script>
<script src="{{asset('admin-vendor/plugins/jquery-validation/localization/messages_ru.js')}}"></script>


<!-- Custom Js -->
<script src="{{asset('admin-vendor/js/admin.js')}}"></script>
<script src="{{asset('admin-vendor/js/pages/examples/sign-in.js')}}"></script>
</body>

</html>