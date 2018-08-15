@extends(env('THEME').'.mainlayout.mainlayout')

@section('header')
 <header class="main__header">
        <div class="main-heat__header header__height" id="menu">
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
                                                    <a href="{{ route('home') }}">
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
            <script type="text/javascript">
	            $(".alert").click(function(){
	                alert('Раздел В Стадии Разработки!')
	            });
	        </script>
@endsection

@section('header_content')	
		<div class="main-content_header">
			<div class="main-background-content_header">
				<div class="container-fluid">
					<div class="row">
						<div class="white_row"></div>
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 hidden-xs">
							<img src="{{asset('assets/img/images/PQ_cat.png')}}" class="header_content_logo">
						</div>
						<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
							<div class="main-info-content_header">
								<h3>До следующей игры осталось</h3>
								<h4>Спешите Зарегистрировать команду</h4>
							</div>
							<div class="main-game_timer">
							@if($closer_game != "None")
			                    <?php 
			                        $time = $closer_game->game_time;
			                        $time = explode(':', $time);
			                        
			                        $date = $closer_game->game_date;
			                        $date = explode('-', $date);
			                    ?>
			                        <script type="text/javascript">
			                                jQuery(document).ready(function() {
			                                    jQuery(".eTimer").eTimer({
			                                        etType: 0,
			                                        etDate: "{{$date[2]}}.{{$date[1]}}.{{$date[0]}}.{{$time[0]}}.{{$time[1]}}",
			                                    });
			                                });
			                        </script>
			                        <div class="eTimer"></div>
									<div class="main-info-content_header">
										<a class="button blue_button button_margin" href="{{ route('Game', ['game_id'=>$closer_game->id]) }}">Ближайшая игра</a>
										<div class="main-smart_call-content_header">
											<a href="tel:(093) 589-09-32" class="smart_call-cursor"><img src="{{asset('assets/img/icons/Smart_call.png')}}" class="smart_call"></a>
										</div>
									</div>
			                    @else
			                        <script type="text/javascript">
			                                jQuery(document).ready(function() {
			                                    jQuery(".eTimer").eTimer({
			                                        etType: 0,
			                                        etDate: "0.0.0.0.0",
			                                    });
			                                });
			                        </script>
			                        <div class="eTimer"></div>
									<div class="main-info-content_header">
										<a class="button blue_button button_margin" href="#">Ближайшая игра Отсутствует</a>
										<div class="main-smart_call-content_header">
											<a href="tel:(093) 589-09-32" class="smart_call-cursor"><img src="{{asset('assets/img/icons/Smart_call.png')}}" class="smart_call"></a>
										</div>
									</div>
			                @endif
									
							</div>
						</div>
						<div class="hidden-lg hidden-md hidden-sm col-xs-12">
							<img src="{{asset('assets/img/images/PQ_cat.png')}}" class="header_content_logo">
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
@endsection

@section('content')
	<main class="main_content">
		<div class="main-rules_content hidden-sm hidden-xs">
			<script>new WOW().init();</script>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h2 class="rules_ask animated wow fadeIn" data-wow-offset="300" data-wow-duration="2s">
							Что такое Квиз?
						</h2>
					</div>
					<div class="col-md-6">
						<div class="main-box_rule_content wow animated fadeIn" data-wow-offset="300"  data-wow-duration="2.5s">
							<div class="img-box-rule">
								<img src="{{asset('assets/img/icons/PubQuiz-03.png')}}" alt="">
							</div>
							<div class="text-box-rule">
								До 6 человек в команде;
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="main-box_rule_content wow animated fadeIn" data-wow-offset="450"  data-wow-duration="2.5s">
							<div class="img-box-rule">
								<img src="{{asset('assets/img/icons/PubQuiz-04.png')}}" alt="">
							</div>
							<div class="text-box-rule">
								Текстовые, графические, аудио и видео раунды;
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="main-box_rule_content animated wow fadeIn" data-wow-offset="400"  data-wow-duration="2.5s">
							<div class="img-box-rule">
								<img src="{{asset('assets/img/icons/PubQuiz-05.png')}}" alt="">
							</div>
							<div class="text-box-rule">
								2-3 часа, 70-100 вопросов на разные темы;
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="main-box_rule_content wow animated fadeIn" data-wow-offset="450"  data-wow-duration="2.5s">
							<div class="img-box-rule">
								<img src="{{asset('assets/img/icons/PubQuiz-06.png')}}" alt="">
							</div>
							<div class="text-box-rule">
								Каждую неделю сотни людей повышают IQ вместе с нами!
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="main-buttons_rules_content">
				<div class="container">
					<div class="row">
						<div class="col-md-3 col-md-offset-3 col-sm-3 col-sm-offset-3 right">
							<a href="{{ route('rules') }}" class="button blue_button special_ask_button animated wow slideInLeft" data-wow-offset="300" data-wow-duration="2s" style="display:inline-block; opacity:0;">Правила</a>
						</div>
						<div class="col-md-3 col-sm-3"><a href="#" class="button text_button animated wow slideInRight" data-wow-offset="300" data-wow-duration="2s" style="display:inline-block; opacity:0;">Примеры заданий</a></div>
					</div>
				</div>
			</div>	
		</div>
		<div class="main-rules_content hidden-lg hidden-md ">
			<script>new WOW().init();</script>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h2 class="rules_ask">
							Что такое Квиз?
						</h2>
					</div>
					<div class="col-md-6">
						<div class="main-box_rule_content">
							<div class="img-box-rule">
								<img src="{{asset('assets/img/icons/PubQuiz-03.png')}}" alt="">
							</div>
							<div class="text-box-rule">
								До 6 человек в команде;
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="main-box_rule_content">
							<div class="img-box-rule">
								<img src="{{asset('assets/img/icons/PubQuiz-04.png')}}" alt="">
							</div>
							<div class="text-box-rule">
								Текстовые, графические, аудио и видео раунды;
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="main-box_rule_content">
							<div class="img-box-rule">
								<img src="{{asset('assets/img/icons/PubQuiz-05.png')}}" alt="">
							</div>
							<div class="text-box-rule">
								2-3 часа, 70-100 вопросов на разные темы;
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="main-box_rule_content">
							<div class="img-box-rule">
								<img src="{{asset('assets/img/icons/PubQuiz-06.png')}}" alt="">
							</div>
							<div class="text-box-rule">
								Каждую неделю сотни людей повышают IQ вместе с нами!
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="main-buttons_rules_content">
				<div class="container">
					<div class="row">
						<div class="col-md-3 col-md-offset-3 col-sm-3 col-sm-offset-3 col-xs-12 right">
							<a href="#" class="button blue_button special_ask_button">Правила</a>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 left"><a href="#" class="button text_button">Примеры заданий</a></div>
					</div>
				</div>
			</div>	
		</div>
		<div class="main-list_game">
			<div class="main-background_list_game">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h2 class="center">Расписание Игр</h2>
						</div>
						@if($closer_games == "None")
							<h2 style="position: relative;top: 105px;margin-bottom: 200px;text-align: center;">Игры отсутсвуют</h2>
						@else
						<?php $counter = 0;$first_array = [];$second_array = [];$theard_array = [];
							foreach ($closer_games as $cg) {
								if($counter < 3){
									array_push($first_array,$cg);
									$counter++;
								}
								if($counter >= 3 && $counter < 6){
									array_push($second_array,$cg);
									$counter++;
								}
								if($counter >= 6 && $counter < 9){
									array_push($theard_array,$cg);
									$counter++;
								}
							}
						 ?>
						<div class="slider">
							<!--Slide start-->
							@if(count($first_array) != 0)
							<?php $counter = 0; ?>
							<div class="slide item">
								<div class="slide-content">
									<div class="container">
										<div class="row">
											<div class="col-md-10 col-sm-10 col-xs-10 col-sm-offset-1 col-xs-offset-1">
												<div class="row content_popular">
													@foreach($first_array as $game)
													<div class="col-md-4 col-md-offset-0 col-sm-offset-2 col-sm-8 col-xs-8 col-xs-offset-2">
														<a href="{{ route('Game', ['game_id'=>$game->id]) }}" class="main-box-list-game" style="cursor:pointer;">
					                                        <div class="main-box-list-game_background" style="background-image: url({{$game->game_img_path}});cursor:pointer;">  
					                                            <h3>{{ $game->game_name }}</h3>
					                                            <p class="main-box-list-game_p">{{ $game->game_date }} в {{ $game->game_time }}</p>
					                                            <h4 class="center" style="margin: 40px 0 -20px;">Столиков Занято</h4>
					                                            
					                                            <div class="center">
					                                                <div class="pie">
					                                                    <div class="clip1">
					                                                        <div class="slice1 slice{{ $game->id }}1"></div>
					                                                    </div>
					                                                    <div class="clip2">
					                                                        <div class="slice2 slice{{ $game->id }}2"></div>
					                                                    </div>
					                                                    <div class="status">{{ $game->disable_table }} /  {{ $game->all_table }}</div>
					                                                </div>
					                                            </div>
					                                            <script type="text/javascript">
					                                                $(document).ready(function () {
					                                                    progressBarUpdate({{ $game->disable_table }}, {{ $game->all_table }},"slice{{ $game->id }}1","slice{{ $game->id }}2");
					                                                });
					                                            </script>
					                                        </div>     
					                                    </a>	
													</div>
													<?php $counter++; ?>
													@endforeach
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							@endif
							<!--Slide end -->

							<!--Slide start-->
							@if(count($second_array) != 0)
							<?php $counter = 0; ?>
							<div class="slide item">
								<div class="slide-content">
									<div class="container">
										<div class="row">
											<div class="col-md-10 col-sm-10 col-xs-10 col-sm-offset-1 col-xs-offset-1">
												<div class="row content_popular">
													@foreach($second_array as $game)
													<div class="col-md-4 col-md-offset-0 col-sm-offset-2 col-sm-8 col-xs-8 col-xs-offset-2">
														<a href="{{ route('Game', ['game_id'=>$game->id]) }}" class="main-box-list-game">
					                                        <div class="main-box-list-game_background" style="background-image: url({{$game->game_img_path}});cursor:pointer;">  
					                                            <h3>{{ $game->game_name }}</h3>
					                                            <p class="main-box-list-game_p">{{ $game->game_date }} в {{ $game->game_time }}</p>
					                                            <h4 class="center" style="margin: 40px 0 -20px;">Столиков Занято</h4>
					                                            
					                                            <div class="center">
					                                                <div class="pie">
					                                                    <div class="clip1">
					                                                        <div class="slice1 slice{{ $game->id }}1"></div>
					                                                    </div>
					                                                    <div class="clip2">
					                                                        <div class="slice2 slice{{ $game->id }}2"></div>
					                                                    </div>
					                                                    <div class="status">{{ $game->disable_table }} /  {{ $game->all_table }}</div>
					                                                </div>
					                                            </div>
					                                            <script type="text/javascript">
					                                                $(document).ready(function () {
					                                                    progressBarUpdate({{ $game->disable_table }}, {{ $game->all_table }},"slice{{ $game->id }}1","slice{{ $game->id }}2");
					                                                });
					                                            </script>
					                                        </div>     
					                                    </a>	
													</div>
													<?php $counter++; ?>
													@endforeach
													@if($counter < 3)	
														<div class="col-md-4 col-md-offset-0 col-sm-offset-2 col-sm-8 col-xs-8 col-xs-offset-2">
															<div class="main-box-list-game" style="cursor:pointer;">
																<h3>Все игры</h3>
																<p>Смотреть все</p>
																<p class="main-box-list-game_margin"><a href="{{ route('GameCalendar') }}" class="button">Перейти</a></p>
															</div>
														</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							@endif
							<!--Slide end -->
							@if(count($theard_array) != 0)
							<?php $counter = 0; ?>
							<div class="slide item">
								<div class="slide-content">
									<div class="container">
										<div class="row">
											<div class="col-md-10 col-sm-10 col-xs-10 col-sm-offset-1 col-xs-offset-1">
												<div class="row">
													@foreach($second_array as $game)
														<div class="col-md-4 col-md-offset-0 col-sm-offset-2 col-sm-8 col-xs-8 col-xs-offset-2">
															<a href="{{ route('Game', ['game_id'=>$game->id]) }}" class="main-box-list-game">
					                                        <div class="main-box-list-game_background" style="background-image: url({{$game->game_img_path}});cursor:pointer;">  
					                                            <h3>{{ $game->game_name }}</h3>
					                                            <p class="main-box-list-game_p">{{ $game->game_date }} в {{ $game->game_time }}</p>
					                                            <h4 class="center" style="margin: 40px 0 -20px;">Столиков Занято</h4>
					                                            
					                                            <div class="center">
					                                                <div class="pie">
					                                                    <div class="clip1">
					                                                        <div class="slice1 slice{{ $game->id }}1"></div>
					                                                    </div>
					                                                    <div class="clip2">
					                                                        <div class="slice2 slice{{ $game->id }}2"></div>
					                                                    </div>
					                                                    <div class="status">{{ $game->disable_table }} /  {{ $game->all_table }}</div>
					                                                </div>
					                                            </div>
					                                            <script type="text/javascript">
					                                                $(document).ready(function () {
					                                                    progressBarUpdate({{ $game->disable_table }}, {{ $game->all_table }},"slice{{ $game->id }}1","slice{{ $game->id }}2");
					                                                });
					                                            </script>
					                                        </div>     
					                                    </a>	
														</div>
														<?php $counter++; ?>
													@endforeach
													@if($counter < 3)	
														<div class="col-md-4 col-md-offset-0 col-sm-offset-2 col-sm-8 col-xs-8 col-xs-offset-2">
															<div class="main-box-list-game" style="cursor:pointer;">
																<h3>Все игры</h3>
																<p>Смотреть все</p>
																<p class="main-box-list-game_margin"><a href="{{ route('GameCalendar') }}" class="button">Перейти</a></p>
															</div>
														</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							@endif
							<!--Slide end -->


							
						</div>
						@endif
					</div>
				</div>
				<div class="container">
					<div class="row">
						<div class="main-ask_game">
							<h2 class="center">Хотите организовать PubQuiz в своем городе?</h2>
							<p class="center"><a class="button button_ask get_corporate_game">Хочу!</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		
	</main>
@endsection

