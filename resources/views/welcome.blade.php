<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/pic.png') }}" type="image/x-icon">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Телефонный справочник МГУ им. Н.П. Огарёва</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" @scroll="onScroll">
    <vue-progress-bar></vue-progress-bar>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel"
         style="position: fixed; top: 0; left: 0; width: 100%; z-index: 100;">
        <div class="container">
            <router-link class="navbar-brand" to="/">
                <img src="{{ asset('images/logo-tel.jpg') }}" height="50px;" alt="">
            </router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @if (Auth::user() && Auth::user()->role)
                        <li class="nav-item">
                            <router-link class="nav-link" to="/search">Телефонный справочник <span class="sr-only">(current)</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/admin/buildings">Здания <span
                                    class="sr-only">(current)</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/admin/groups">Группы <span
                                    class="sr-only">(current)</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/admin/people">Сотрудники <span
                                    class="sr-only">(current)</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/admin/users">Пользователи <span
                                    class="sr-only">(current)</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/admin/emergency">Дежурные службы <span
                                    class="sr-only">(current)</span>
                            </router-link>
                        </li>
                    @elseif (Auth::user() && !Auth::user()->role)
                        <li class="nav-item">
                            <router-link class="nav-link" to="/search">Телефонный справочник <span class="sr-only">(current)</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/admin/people">Сотрудники <span
                                    class="sr-only">(current)</span>
                            </router-link>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <router-link class="nav-link lb-link" to="/emergency">Дежурные
                                службы
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link lb-link" to="/constructions">Адреса
                                объектов университета
                                <span
                                    class="sr-only">(current)
                                </span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link hidden-link-mobile lb-link" to="/search">Классический вид
                                справочника
                            </router-link>


                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Выйти
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 mt-5 mb-sm-5">
        <router-view></router-view>
    </main>
    <footer>
        <div class="container d-flex justify-content-around align-items-center">
            <p class="hidden-p">
                <i class="fab fa-internet-explorer"></i>
                <a href="https://ci.mrsu.ru">
                    <span>ci.mrsu.ru</span>
                </a>
            </p>
            <p class="hidden-p">
                <i class="fa fa-envelope"></i>
                <a href="mailto:ic@mrsu.ru">
                    <span>ic@mrsu.ru</span>
                </a>
            </p>
            <p class="hidden-p">
                <i class="fab fa-vk"></i>
                <a href="https://vk.com/cimrsu">
                    <span>vk.com/cimrsu</span>
                </a>
            </p>
            <p class="hidden-p">
                <i class="fa fa-phone"></i>
                <a href="tel:+78342777250">
                    <span>+7 (8342) 777-250</span>
                </a>
            </p>
            <p class="d-flex align-items-center">
                <span class="white-text">MADE BY&nbsp;</span>
                <img src="{{ asset('images/mini-white-logo.png') }}" width="18px" height="18px" alt="">
                <span class="white-text">&nbsp;CENTER INTERNET</span>
            </p>
        </div>
    </footer>
</div>
</body>
</html>
