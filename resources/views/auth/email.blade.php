@extends(env('THEME').'.mainlayout.mainlayout')

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
 {!!$navigation!!} 
@endsection

@section('header_content')
  
@endsection

@section('hid')
@endsection

@section('header')
    <header class="main__header">
        <div class="main-heat__header header__height position_relative" id="menu">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 header__height">
                        <a href="#"><img src="{{asset('assets/img/icons/PubQuiz_logo.png')}}" alt="" class="main_logo"></a>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6 header__height">
                        <div class="main-pannel-heat__header hidden-sm hidden-xs">
                            <div class="main-pannel_content-heat__header">
                                <div class="btn-group dropdown">
                                    <div class="btn-group pull-right">
                                        <div class="btn choose-transparent">
                                            Киев
                                        </div>
                                        <button class="btn choose-transparent choose-caret dropdown-toggle" data-toggle="dropdown">
                                             <i class="caret"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="#">Львов</a>
                                                <a href="#">Орск</a>
                                                <a href="#">Запорожье</a>
                                            </li>               
                                        </ul>
                                    </div>
                                </div>
                                <div class="autorithation">
                                    <a href="{{ route('login') }}">Вход <span class="round"><i class="fas fa-user"></i></span><span class="hidde">.</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="main-menu-heat__header hidden-sm hidden-xs">
                            <ul class="main-menu-navigation-heat__header">
                                <li><a href="#" class="active_button">Главная</a></li>
                                <li><a href="#">Правила</a></li>
                                <li><a href="#">Календарь игр</a></li>
                                <li><a href="#">Рейтинг</a></li>
                                <li><a href="#">Корпоративные игры</a></li>
                                <li><a href="#">Магазин</a></li>
                                <li><a href="#">Контакты</a></li>
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
@endsection  

@section('hid')
@show

@section('content')
    <main class="main_auth">
    <div class="auth_content">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}" class="auth_form">
                    {{ csrf_field() }}

                        <div class="panel panel-default">
                            <div class="panel-heading">Сброс пароля</div>

                            <div class="panel-body">            
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">E-Mail</label>

                                        <div class="col-md-7">
                                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                Отправить ссылку со сбросом пароля
                                            </button>
                                        </div>
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

