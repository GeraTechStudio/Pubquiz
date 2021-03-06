@extends(config('EnvSettings.THEME').'.mainlayout.mainlayout')

@section('add_style')
    <style type="text/css">
        html{
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }
        body{
            display: flex;
            flex: auto;
            flex-direction: inherit;
            justify-content: space-between;
        }
    </style>
@endsection


@section('header')
 {!!$region!!}
@endsection 

@section('navigation')
<div class="main-menu-heat__header hidden-sm hidden-xs">
                                <ul class="main-menu-navigation-heat__header">
                                    <li><a href="{{ url('/') }}">Главная</a></li>
                                    <li><a href="{{ route('rules') }}">Правила</a></li>
                                    <li><a href="{{ route('GameCalendar') }}">Календарь игр</a></li>
                                    <li><a href="{{ route('rating') }}">Рейтинг</a></li>
                                    <li><a href="#get_corporate_game" class="get_corporate_game">Корпоративные игры</a></li>
                                    <li><a href="#alert" class="alert">Магазин</a></li>
                                    <li><a href="{{ route('contacts') }}">Контакты</a></li>
                                </ul>
                            </div>
                            <div class="navbar-header hidden-lg hidden-md">             
                                <button type="button" class="navbar-toggle_menu mobile-mnu">
                                    <span class="sr-only">Открыть навигацию</span>
                                    <span class="icon-bars first"></span>
                                    <span class="icon-bars"></span>
                                    <span class="icon-bars"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <script type="text/javascript">
            $(".alert").click(function(){
                alert('Раздел В Стадии Разработки!')
            });
        </script>          
@endsection

@section('header_content')  
@endsection

@section('hid')
@show

@section('content')
<main class="main_auth">
    <div class="auth_content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    @if (session()->has('message'))
                        <div class="alert alert-info">{{ session('message') }}</div>
                    @endif
                    <h1>Войти</h1>
                    <form method="POST" action="{{ route('login') }}" class="auth_form">
                    {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                            <div class="col-md-10 col-md-offset-1">
                                <input id="login" type="name" class="form-control" name="login" value="{{ old('login') }}" placeholder="Логин" required autofocus>

                                @if ($errors->has('login'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('login')}}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-md-10 col-md-offset-1">
                                <input id="password" type="password" class="form-control" name="password" placeholder="Пароль" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="inline inline_ask">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Запомнить меня
                                        </label>
                                    </div>
                                
                                    <a class="" href="{{ route('password.request') }}">
                                        Забыли свой пароль?
                                    </a>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="inline">
                                    <button type="submit" class="button_auth" style="margin:0px 10px; width: 190px;">
                                        Войти
                                    </button>
                                    <a href="{{ route('register') }}" class="button_auth" style="margin:0px 10px">
                                        Зарегистрироваться
                                    </a>
                                </div>
                            </div>
                        </div>
            
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection






