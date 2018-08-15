	<header class="main__header">
        <div class="main-heat__header header__height position_relative" id="menu">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 header__height">
                        <a href="https://pubquiz.me"><img src="{{asset('assets/img/icons/PubQuiz_logo.png')}}" alt="" class="main_logo"></a>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6 header__height">
                        <div class="main-pannel-heat__header hidden-sm hidden-xs">
                            <div class="main-pannel_content-heat__header">
                                <div class="btn-group dropdown">
                                    <div class="btn-group pull-right">
                                        <div class="btn choose-transparent dsbl">
                                            {{config('EnvSettings.TRANSLATE')}}
                                        </div>
                                        <button class="btn choose-transparent choose-caret dropdown-toggle" data-toggle="dropdown">
                                             <i class="caret"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                            @if($regions != false)
                                            	@foreach($regions as $region)
	                                                <a href="{{ $region->link }}">{{ $region->region }}</a>
                                                @endforeach
                                            @endif
                                            </li>               
                                        </ul>
                                    </div>
                                </div>
                                <div class="autorithation">
	    							@if (Auth::guest())
			                            <a href="{{ route('login') }}">Вход <span class="round"><i class="fas fa-user"></i></span><span class="hidde">.</span></a>
			                        @else
			                            <li class="dropdown pull-right" style="list-style: none">
			                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			                                    <span id="user_name">{{ Auth::user()->name }}</span> 
                                                @if(Auth::user()->user_img_path == "None")
                                                    <span class="round"><i class="fas fa-user"></i></span><span class="hidde">.</span>
                                                @else 
                                                    <span class="round user_avatar" style="background-image: url('{{Auth::user()->user_img_path }}'); background-position: center center;"></span><span class="hidde">.</span>
                                                @endif
			                                </a>

			                                <ul class="dropdown-menu" role="menu" style="min-width: 120px">
			                                    <li>
                                                    <a href="{{ url('/home') }}">
                                                        Личный Кабинет
                                                    </a>
			                                        <a href="{{ route('logout') }}"
			                                            onclick="event.preventDefault();
			                                                     document.getElementById('logout-form').submit();">
			                                            Выйти
			                                        </a>

			                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			                                            {{ csrf_field() }}
			                                        </form>
			                                    </li>
			                                </ul>
			                            </li>
			                        @endif
								</div>
                            </div>
                        </div>
