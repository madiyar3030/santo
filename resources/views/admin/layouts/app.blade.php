<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{$title ? $title.' | ' : ''}}Santo Админ-панель</title>
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

    <!-- Morris Chart Css-->
    <link href="{{asset('admin-vendor/plugins/morrisjs/morris.css')}}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{asset('admin-vendor/css/style.css')}}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{asset('admin-vendor/css/themes/all-themes.css')}}" rel="stylesheet" />
    @stack('css')
</head>

<body class="theme-red">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Please wait...</p>
    </div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Search Bar -->
<div class="search-bar">
    <div class="search-icon">
        <i class="material-icons">search</i>
    </div>
    <input type="text" placeholder="START TYPING...">
    <div class="close-search">
        <i class="material-icons">close</i>
    </div>
</div>
<!-- #END# Search Bar -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="">Santo - Админ-панель</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- #Top Bar -->
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="{{asset('admin-vendor/images/user.png')}}" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <?php $admin = session()->get('admin')?>
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$admin->name}}</div>
                <div class="email">{{/*$admin->username*/ $admin->name}}</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="{{route('signOut')}}"><i class="material-icons">input</i>Выйти</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">НАВИГАЦИЯ</li>
                @if (request()->is('admin*'))
                <li class="{{request()->is('admin') ? 'active' : ''}}">
                    <a href="{{route('viewIndex')}}">
                        <i class="material-icons">home</i>
                        <span>Главная</span>
                    </a>
                </li> {{--MAIN--}}
                <li class="{{request()->is('admin/managers*') || request()->is('admin/authors*') || request()->is('admin/users*') ? 'active' : ''}}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">people</i>
                            <span>Пользователи</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{request()->is('admin/managers*') ? 'active' : ''}}">
                                <a href="{{route('managers.index')}}">
                                    <span>Администраторы</span>
                                </a>
                            </li> {{--MANAGERS--}}
                            <li class="{{request()->is('admin/authors*') ? 'active' : ''}}">
                                <a href="{{route('authors.index')}}">
                                    <span>Авторы</span>
                                </a>
                            </li> {{--AUTHORS--}}
                            <li class="{{request()->is('admin/users*') ? 'active' : ''}}">
                                <a href="{{route('users.index')}}">
                                    <span>Пользователи</span>
                                </a>
                            </li> {{--USERS--}}
                        </ul>
                    </li> {{--PEOPLE--}}
                <li class="{{request()->is('admin/articles*') ? 'active' : ''}}">
                    <a href="{{route('articles.index')}}">
                        <i class="material-icons">chrome_reader_mode</i>
                        <span>Статьи и новости</span>
                    </a>
                </li> {{--ARTICLES--}}
                <li class="{{request()->is('admin/forumCats*') || request()->is('admin/forums*') || request()->is('admin/forummods*') ? 'active' : ''}}">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">forum</i>
                        <span>Форумы</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="{{request()->is('admin/forumCats*') || request()->is('admin/forums*') ? 'active' : ''}}">
                            <a href="{{route('forumCats.index')}}">
                                <span>Форумы</span>
                            </a>
                        </li>
                        <li class="{{request()->is('admin/forummods*') ? 'active' : ''}}">
                            <a href="{{route('forummods.index')}}">
                                <span>Модерация форумов</span>
                            </a>
                        </li>
                    </ul>
                </li> {{--FORUMS--}}
                <li class="{{request()->is('admin/consultations*') || request()->is('admin/consultationmods*') ? 'active' : ''}}">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">live_help</i>
                        <span>Вопросы врачу</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="{{request()->is('admin/consultations*') ? 'active' : ''}}">
                            <a href="{{route('consultations.index')}}">
                                <span>Список</span>
                            </a>
                        </li>
                        <li class="{{request()->is('admin/consultationmods*') ? 'active' : ''}}">
                            <a href="{{route('consultationmods.index')}}">
                                <span>Модерация</span>
                            </a>
                        </li>
                    </ul>
                </li> {{--CONSULTATIONS--}}
                <li class="{{request()->is('admin/events*') || request()->is('admin/eventmods*') ? 'active' : ''}}">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">event</i>
                        <span>Мероприятия</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="{{request()->is('admin/events*') ? 'active' : ''}}">
                            <a href="{{route('events.index')}}">
                                <span>Список</span>
                            </a>
                        </li>
                        <li class="{{request()->is('admin/eventmods*') ? 'active' : ''}}">
                            <a href="{{route('eventmods.index')}}">
                                <span>Модерация</span>
                            </a>
                        </li>
                    </ul>
                </li> {{--EVENTS--}}
                <li class="{{request()->is('admin/feedings*') || request()->is('admin/feedingCats*') ? 'active' : ''}}">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">restaurant_menu</i>
                        <span>Кормление</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="{{request()->is('admin/feedingCats*') ? 'active' : ''}}">
                            <a href="{{route('feedingCats.index')}}">
                                <span>Категории</span>
                            </a>
                        </li>
                        <li class="{{request()->is('admin/feedings*') ? 'active' : ''}}">
                            <a href="{{route('feedings.index')}}">
                                <span>Список</span>
                            </a>
                        </li>
                    </ul>
                </li> {{--FEEDINGS--}}
                <li class="{{request()->is('admin/playlists*') ? 'active' : ''}}">
                    <a href="{{route('playlists.index')}}">
                        <i class="material-icons">accessibility</i>
                        <span>Медитация</span>
                    </a>
                </li> {{--PLAYLISTS--}}
                <li class="{{request()->is('admin/vaccines*') ? 'active' : ''}}">
                    <a href="{{route('vaccines.index')}}">
                        <i class="material-icons">local_hospital</i>
                        <span>Прививка</span>
                    </a>
                </li> {{--VACCINES--}}
                <li class="{{request()->is('admin/blogs*') ? 'active' : ''}}">
                    <a href="{{route('blogs.index')}}">
                        <i class="material-icons">textsms</i>
                        <span>Блог специалиста</span>
                    </a>
                </li> {{--BLOGS--}}
                <li class="{{request()->is('admin/tags*') ? 'active' : ''}}">
                    <a href="{{route('tags.index')}}">
                        <i class="material-icons">menu</i>
                        <span>Теги</span>
                    </a>
                </li> {{--TAGS--}}
                <li class="{{request()->is('admin/notes*') ? 'active' : ''}}">
                    <a href="{{route('notes.index')}}">
                        <i class="material-icons">priority_high</i>
                        <span>Примечания</span>
                    </a>
                </li> {{--NOTES--}}
                <li class="{{request()->is('admin/comments*') ? 'active' : ''}}">
                    <a href="{{route('comments.index')}}">
                        <i class="material-icons">comment</i>
                        <span>Комментарии</span>
                    </a>
                </li> {{--COMMENTS--}}
                @else
                    <li class="{{request()->is('blogger/blogs*') ? 'active' : ''}}">
                        <a href="{{route('bloggers.index')}}">
                            <i class="material-icons">textsms</i>
                            <span>Блог специалиста</span>
                        </a>
                    </li> {{--BLOGS--}}
                @endif
            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; {{'2019 - '.date("Y")}} <a href="javascript:void(0);">Santo</a>.
            </div>
            <div class="version">
                <b>Version: </b> 1.0.1
            </div>
        </div>
    </aside>
    <aside id="rightsidebar" class="right-sidebar">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="skins">
                <ul class="demo-choose-skin">
                    <li data-theme="red" class="active">
                        <div class="red"></div>
                        <span>Red</span>
                    </li>
                    <li data-theme="pink">
                        <div class="pink"></div>
                        <span>Pink</span>
                    </li>
                    <li data-theme="purple">
                        <div class="purple"></div>
                        <span>Purple</span>
                    </li>
                    <li data-theme="deep-purple">
                        <div class="deep-purple"></div>
                        <span>Deep Purple</span>
                    </li>
                    <li data-theme="indigo">
                        <div class="indigo"></div>
                        <span>Indigo</span>
                    </li>
                    <li data-theme="blue">
                        <div class="blue"></div>
                        <span>Blue</span>
                    </li>
                    <li data-theme="light-blue">
                        <div class="light-blue"></div>
                        <span>Light Blue</span>
                    </li>
                    <li data-theme="cyan">
                        <div class="cyan"></div>
                        <span>Cyan</span>
                    </li>
                    <li data-theme="teal">
                        <div class="teal"></div>
                        <span>Teal</span>
                    </li>
                    <li data-theme="green">
                        <div class="green"></div>
                        <span>Green</span>
                    </li>
                    <li data-theme="light-green">
                        <div class="light-green"></div>
                        <span>Light Green</span>
                    </li>
                    <li data-theme="lime">
                        <div class="lime"></div>
                        <span>Lime</span>
                    </li>
                    <li data-theme="yellow">
                        <div class="yellow"></div>
                        <span>Yellow</span>
                    </li>
                    <li data-theme="amber">
                        <div class="amber"></div>
                        <span>Amber</span>
                    </li>
                    <li data-theme="orange">
                        <div class="orange"></div>
                        <span>Orange</span>
                    </li>
                    <li data-theme="deep-orange">
                        <div class="deep-orange"></div>
                        <span>Deep Orange</span>
                    </li>
                    <li data-theme="brown">
                        <div class="brown"></div>
                        <span>Brown</span>
                    </li>
                    <li data-theme="grey">
                        <div class="grey"></div>
                        <span>Grey</span>
                    </li>
                    <li data-theme="blue-grey">
                        <div class="blue-grey"></div>
                        <span>Blue Grey</span>
                    </li>
                    <li data-theme="black">
                        <div class="black"></div>
                        <span>Black</span>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
</section>
@yield('content')

<!-- Jquery Core Js -->
<script src="{{asset('admin-vendor/plugins/jquery/jquery.min.js')}}"></script>

<!-- Bootstrap Core Js -->
<script src="{{asset('admin-vendor/plugins/bootstrap/js/bootstrap.js')}}"></script>

<!-- Select Plugin Js -->
<script src="{{asset('admin-vendor/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

<!-- Slimscroll Plugin Js -->
<script src="{{asset('admin-vendor/plugins/jquery-slimscroll/jquery.slimscroll.js')}}"></script>

<!-- Waves Effect Plugin Js -->
<script src="{{asset('admin-vendor/plugins/node-waves/waves.js')}}"></script>

<!-- Jquery CountTo Plugin Js -->
<script src="{{asset('admin-vendor/plugins/jquery-countto/jquery.countTo.js')}}"></script>

<!-- Custom Js -->
<script src="{{asset('admin-vendor/js/admin.js')}}"></script>

<!-- Demo Js -->
<script src="{{asset('admin-vendor/js/demo.js')}}"></script>
@stack('js')
</body>

</html>
