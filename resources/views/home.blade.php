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
    <section class="scripts">
        <script src="{{asset('assets/libs/Autocmplete/jquery-ui.js')}}"></script>
    </section>
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


@section('content')
<main class="home_page">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-10 col-md-10">
                <ol id="breadcrumb">
                  <li>
                    <a href="#"><i class="fas fa-home"></i>
                    <span class="sr-only">Главная</span></a>
                  </li>
                  <li class="current">
                    <a href="#">Личный Кабинет</a>
                  </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-2 col-md-2">
                <div class="home_main_box">
                    <div class="home_user_box">
                        @if(Auth::user()->user_img_path == "None")
                            <div class="home_user_box-img">
                                <i class="fas fa-user"></i>
                            </div>
                        @else
                            <div class="home_user_box-img" style="background-image: url('{{Auth::user()->user_img_path }}'); background-position: center center;"></div>
                        @endif
                        <form enctype="multipart/form-data" id="User_img"  name="User_img">
                            <div class="file-upload img-transparent" id="empty_zone">
                                <label id="buf">
                                    <input type="file" id="user_avatar" name="user_avatar" accept="image/*">
                                    <span>Выберите файл</span>
                                </label>
                            </div>
                        </form>
                        <div class="home_user_box-title">
                            {{Auth::user()->login}}
                        </div>
                    </div>
                    <div class="home_navigation">
                        <div id="accordion" class="panel-group">
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#collapse-1" data-parent="#accordion" data-toggle="collapse">Редактировать</a>
                                </h4>
                            </div>
                            <div id="collapse-1" class="panel-collapse collapse out">
                                <div class="panel-body">
                                    <div class="link_box">
                                        <a id="edit_profile_user">Профиль</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#collapse-2" data-parent="#accordion" data-toggle="collapse">Мои Игры</a>
                                </h4>
                            </div>
                            <div id="collapse-2" class="panel-collapse collapse out">
                                <div class="panel-body">
                                    <div class="link_box">
                                        <a href="#">Ближайшие игры</a>
                                        <a href="#">История моих Игр</a>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="home_game_boxs">
                    <?php $visible_block = 0; ?>
                    @if(count($temporary_commands) != 0)
                        @foreach($temporary_commands as $temporary_command)
                                <div class="home_game__one_box applyer_box{{ $temporary_command->id }}">
                                    <div class='home_game_one_box_img'>
                                        <div class='update_command_img img_after_update_temp{{ $visible_block }}'>
                                            @if($temporary_command->command_img_path == "None")
                                            <i class='fas fa-smile'></i>
                                            @else
                                                <img class='command_img_temp{{$visible_block}}' src='{{$temporary_command->command_img_path }}'>
                                            @endif
                                        </div>
                                    </div>
                                    <div id='accordion' class='panel-group applyer_box{{ $temporary_command->id }}'>
                                        <div class='panel'>
                                            <div class='panel-heading panel_game' style="background-color: rgba(255, 152, 0, 0.77); ">
                                                <h4 class='panel-title'>
                                                    <a href='#game-temp{{ $visible_block }}' data-parent='#accordion' data-toggle='collapse'>{{$temporary_command->command_name}}</a>
                                                </h4>
                                            </div>
                                            <div id="game-temp{{ $visible_block }}" class="panel-collapse collapse out">
                                                <div class="panel-body">
                                                    <div class="link_box">
                                                        <a class="myCommand" href="{{ route('showMyCommand', ['command_id'=>$temporary_command->id]) }}">Мои Игры</a>
                                                        <a class="myCommandConsist" command-id="{{ $temporary_command->id }}" commander="no" >Состав Команды</a>
                                                        <a class="myCommandExit" command-id="{{ $temporary_command->id }}">Выйти</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            <?php $visible_block++; ?>
                        @endforeach
                    @endif
                    @for($i = 1; $i<6-$visible_block; $i++)
                        <?php $present=false;?>
                        @foreach($commands as $command)
                            @if($command->count == $i)
                                <div class="home_game__one_box">
                                    <div class='home_game_one_box_img'>
                                        <a class="apply_round" title="Заявок В Команду">{{ $command->applyers }}</a>
                                        <div class='update_command_img img_after_update{{ $i }}'>
                                            @if($command->command_img_path == "None")
                                            <i class='fas fa-smile'></i>
                                            @else
                                                <img class='command_img{{$i}}' src='{{$command->command_img_path }}'>
                                            @endif
                                            <form enctype='multipart/form-data' class="command_img">
                                                <div class='file-upload img-transparent' id='empty_zone'>
                                                    <label>
                                                        <input type='file' class='full_box' count-game='{{$i}}' name-game='{{$command->command_name}}' accept='image/*'>
                                                        <span>Выберите файл</span>
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div id='accordion' class='panel-group'>
                                        <div class='panel'>
                                            <div class='panel-heading panel_game'>
                                                <h4 class='panel-title'>
                                                    <a href='#game-{{ $i }}' id="command_link_name{{ $i }}" data-parent='#accordion' data-toggle='collapse'>{{$command->command_name}}</a>
                                                </h4>
                                            </div>
                                            <div id="game-{{ $i }}" class="panel-collapse collapse out">
                                                <div class="panel-body">
                                                    <div class="link_box">
                                                        <a class="command_edit" command-count="{{ $i }}" command-name="{{$command->command_name}}">Переименовать</a>
                                                        <a class="myCommand" href="{{ route('showMyCommand', ['command_id'=>$command->id]) }}" command-id="{{ $command->id }}">Мои Игры</a>
                                                        <a class="myCommandConsist" command-id="{{ $command->id}}" commander="yes">Состав Команды</a>
                                                        <a class="command_delete" command-id="{{ $command->id }}" command-count="{{ $i }}" command-name="{{$command->command_name}}">Удалить</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            <?php $present=true;?>
                            @endif
                        @endforeach
                        @if($present==false) 
                            <div class="home_game__one_box">
                                <div class="home_game_one_box_img empty_box replace_command{{ $i }}">
                                    <div class="add_comand_game command{{ $i }}">
                                        <div class="add_command_round"><span><button type="button" class="btn_create_command" value="{{ $i }}">+</button></span></div>
                                        <div class="add_command_button">Создать команду </div>
                                    </div>
                                </div>
                            </div>
                        @endif   
                    @endfor
                </div>
                    
                    
             </div>
            <div class="col-lg-6 col-md-6">
                <div class="home_content_page">
                    <div class="content_welcome">
                        <h1>Здравствуйте, {{ Auth::user()->name }}!</h1>
                    </div>
                    @if($my_closer_game != "None")
                    <?php 
                        $time = $my_closer_game->game_time;
                        $time = explode(':', $time);
                        
                        $date = $my_closer_game->game_date;
                        $date = explode('-', $date);
                    ?>
                    <div class="content_timer">
                        <h2>До Вашей Ближайшей Игры <br> <span>{{ $my_closer_game->game_name }}</span> осталось </h2>
                        <script type="text/javascript">
                                jQuery(document).ready(function() {
                                    jQuery(".eTimer").eTimer({
                                        etType: 0,
                                        etDate: "{{$date[2]}}.{{$date[1]}}.{{$date[0]}}.{{$time[0]}}.{{$time[1]}}",
                                    });
                                });
                        </script>
                            <div class="eTimer home_timer"></div>
                    </div>
                    @else
                        <div class="content_timer">
                        <h2>У вас еще нет Ближайшей Игры </h2>
                        <script type="text/javascript">
                                jQuery(document).ready(function() {
                                    jQuery(".eTimer").eTimer({
                                        etType: 0,
                                        etDate: "0.0.0.0.0",
                                    });
                                });
                        </script>
                            <div class="eTimer home_timer"></div>
                    </div>
                    @endif
                    <div class="applyers_list" style="margin: 0 15px;">
                        <h2>Подать Заявку в команду</h2>
                        <div class="add_command_form">
                            <div style='display: flex;justify-content: space-around;align-items: center;margin-top:15px;'>
                            <input type='name' autocomplete='off' style='margin-top:6px;' class="form-control has-error add_command_input"  placeholder='Поиск Команды'><button style="margin-top: 5px;" class="btn btn-success apply_command-btn">Попроситься в команду</button></div>
                        </div>  

                        <h2>История Заявок</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Команда</th>
                                    <th scope="col">Статус</th>
                                    <th scope="col">Время Обновления</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applyers as $applyer)
                                    <tr id="applyer{{ $applyer->id }}">
                                        <th scope="row" class="applyer" id="applyer{{ $applyer->id }}">{{ $applyer->command_name }}<div class="mini_box_color"></div></th>
                                        @if($applyer->status == "0")
                                            <td>На рассмотрении</td>    
                                        @else
                                            @if($applyer->status == "1")
                                                <td>Отказано</td>
                                            @else
                                                @if($applyer->status == "2")
                                                    <td>Вы вступили в команду</td>
                                                @else
                                                    @if($applyer->status == "3")
                                                        <td>Вас изгнали из команды</td>
                                                    @else
                                                         @if($applyer->status == "4")
                                                        <td>Вы вышли из команды</td>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                        <td>{{ $applyer->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                   <!--  <div class="content_popular">
                        <h2>Игры Набирающие Популярность</h2>
                        <div class="container-fluid">
                            <div class="row">
                                <?php $count=0; ?>
                                @foreach($closer_games as $game)
                                    <div class="col-sm-5">
                                        <div class="main-box-list-game">
                                            <div class="main-box-list-game_background" style="background-image: url({{$game->game_img_path}});">  
                                                <h3>{{ $game->game_name }}</h3>
                                                <p class="main-box-list-game_p">{{ $game->game_date }} в {{ $game->game_time }}</p>
                                                <h4 class="center" style="margin: 40px 0 -20px;">Столиков Занято</h4>
                                                <script type="text/javascript">
                                                    $(document).ready(function () {
                                                        progressBarUpdate({{ $game->disable_table }}, {{ $game->all_table }},"slice{{ $game->id }}1","slice{{ $game->id }}2");
                                                    });
                                                </script>
                                                <div class="center">
                                                    <div class="pie">
                                                        <div class="clip1">
                                                            <div class="slice1 slice{{ $game->id }}1"></div>
                                                        </div>
                                                        <div class="clip2">
                                                            <div class="slice2 slice{{ $game->id }}2"></div>
                                                        </div>
                                                        <div class="status"></div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div id="accordion" class="panel-group pabs">
                                                <div class="panel">
                                                    <div class="panel-heading panel_game">
                                                        <h4 class="panel-title_popular">
                                                            <a href="#pabs_new-{{ $game->id }}" data-parent="#accordion" data-toggle="collapse">Место Проведения</a>
                                                        </h4>
                                                    </div>
                                                    <div id="pabs_new-{{ $game->id }}" class="panel-collapse collapse out">
                                                        <div class="panel-body">
                                                            <div class="link_box">
                                                                @if($game->locations == "None")
                                                                    <h1>-</h1>
                                                                @else
                                                                <?php $count=0 ; ?>
                                                                    @foreach($game->locations as $location)
                                                                        @if($count == 0)
                                                                            <div class="link_box_elements">
                                                                                <div class="link_box_pab">
                                                                                    <a class="show_pub" pub-id="{{ $location->id}}">{{ $location->Location_name}}</a>
                                                                                </div>
                                                                                <div class="link_box_marker">
                                                                                    <a class="show_pub_map" pub-id="{{ $location->id}}"><i class="fas fa-map-marker-alt"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <hr>
                                                                            <div class="link_box_elements">
                                                                                <div class="link_box_pab">
                                                                                    <a class="show_pub" pub-id="{{ $location->id}}">{{ $location->Location_name}}</a>
                                                                                </div>
                                                                                <div class="link_box_marker">
                                                                                    <a class="show_pub_map" pub-id="{{ $location->id}}"><i class="fas fa-map-marker-alt"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        @endif    
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="panel-heading panel_game reverse">
                                                            <h4 class="panel-title_popular">
                                                                <a href="{{ route('Game', ['game_id'=>$game->id]) }}">Подробнее</a>
                                                            </h4>
                                                        </div>
                                                     </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-sm-2">
                                    Игры
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content_popular New_Game">
                        <h2>Новинки</h2>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-ld-4 col-md-4">
                                    <div class="main-box-list-game">
                                        <div class="main-box-list-game_background" style="background-image: url({{asset('assets/img/images/img3.jpg')}});">  
                                            <h3>Большое Название игры</h3>
                                            <p class="main-box-list-game_p">2018-04-19 в 19:30</p>
                                            <h4 class="center">Столиков Занято</h4>
                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    progressBarUpdate(60, 100,"slice1","slice2");
                                                });
                                            </script>
                                            <div class="center">
                                                <div class="pie">
                                                    <div class="clip1">
                                                        <div class="slice1"></div>
                                                    </div>
                                                    <div class="clip2">
                                                        <div class="slice2"></div>
                                                    </div>
                                                    <div class="status"></div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div id="accordion" class="panel-group pabs">
                                            <div class="panel">
                                                <div class="panel-heading panel_game">
                                                    <h4 class="panel-title_popular">
                                                        <a href="#pabs_new-1" data-parent="#accordion" data-toggle="collapse">Место Проведения</a>
                                                    </h4>
                                                </div>
                                                <div id="pabs_new-1" class="panel-collapse collapse out">
                                                    <div class="panel-body">
                                                        <div class="link_box">
                                                           <div class="link_box_elements">
                                                                <div class="link_box_pab">
                                                                    <a href="#">Шульц</a>
                                                                </div>
                                                                <div class="link_box_marker">
                                                                    <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                                                                </div>
                                                            </div>
                                                                <hr>
                                                            <div class="link_box_elements">
                                                                <div class="link_box_pab">
                                                                    <a href="#">Siti Pab на Шулявке</a>
                                                                </div>
                                                                <div class="link_box_marker">
                                                                    <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                                                                </div>
                                                            </div>
                                                                <hr>
                                                            <div class="link_box_elements">
                                                                <div class="link_box_pab">
                                                                    <a href="#">Подол</a>
                                                                </div>
                                                                <div class="link_box_marker">
                                                                    <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                                                                </div>
                                                            </div>                       
                                                        </div>
                                                    </div>
                                                    <div class="panel-heading panel_game reverse">
                                                        <h4 class="panel-title_popular">
                                                            <a href="#">Подробнее</a>
                                                        </h4>
                                                    </div>
                                                 </div>
                                            </div>
                                        </div>      
                                    </div>
                                </div>
                                <div class="col-ld-4 col-md-4">
                                    <div class="main-box-list-game">
                                        <div class="main-box-list-game_background" style="background-image: url({{asset('assets/img/images/img3.jpg')}});">  
                                            <h3>Большое Название игры</h3>
                                            <p class="main-box-list-game_p">2018-04-19 в 19:30</p>
                                            <h4 class="center">Столиков Занято</h4>
                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    progressBarUpdate(60, 100,"slice1","slice2");
                                                });
                                            </script>
                                            <div class="center">
                                                <div class="pie">
                                                    <div class="clip1">
                                                        <div class="slice1"></div>
                                                    </div>
                                                    <div class="clip2">
                                                        <div class="slice2"></div>
                                                    </div>
                                                    <div class="status"></div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div id="accordion" class="panel-group pabs">
                                            <div class="panel">
                                                <div class="panel-heading panel_game">
                                                    <h4 class="panel-title_popular">
                                                        <a href="#pabs_new-2" data-parent="#accordion" data-toggle="collapse">Место Проведения</a>
                                                    </h4>
                                                </div>
                                                <div id="pabs_new-2" class="panel-collapse collapse out">
                                                    <div class="panel-body">
                                                        <div class="link_box">
                                                           <div class="link_box_elements">
                                                                <div class="link_box_pab">
                                                                    <a href="#">Шульц</a>
                                                                </div>
                                                                <div class="link_box_marker">
                                                                    <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                                                                </div>
                                                            </div>
                                                                <hr>
                                                            <div class="link_box_elements">
                                                                <div class="link_box_pab">
                                                                    <a href="#">Siti Pab на Шулявке</a>
                                                                </div>
                                                                <div class="link_box_marker">
                                                                    <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                                                                </div>
                                                            </div>
                                                                <hr>
                                                            <div class="link_box_elements">
                                                                <div class="link_box_pab">
                                                                    <a href="#">Siti Pab на Шулявке</a>
                                                                </div>
                                                                <div class="link_box_marker">
                                                                    <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                                                                </div>
                                                            </div>
                                                                <hr>
                                                            <div class="link_box_elements">
                                                                <div class="link_box_pab">
                                                                    <a href="#">Siti Pab на Шулявке</a>
                                                                </div>
                                                                <div class="link_box_marker">
                                                                    <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="panel-heading panel_game reverse">
                                                        <h4 class="panel-title_popular">
                                                            <a href="#">Подробнее</a>
                                                        </h4>
                                                    </div>
                                                 </div>
                                            </div>
                                        </div>      
                                    </div>
                                </div>
                                <div class="col-ld-4 col-md-4">
                                    <div class="main-box-list-game">
                                        <div class="main-box-list-game_background" style="background-image: url({{asset('assets/img/images/img3.jpg')}});">  
                                            <h3>Название игры</h3>
                                            <p class="main-box-list-game_p">2018-04-19 в 19:30</p>
                                            <h4 class="center">Столиков Занято</h4>
                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    progressBarUpdate(60, 100,"slice1","slice2");
                                                });
                                            </script>
                                            <div class="center">
                                                <div class="pie">
                                                    <div class="clip1">
                                                        <div class="slice1"></div>
                                                    </div>
                                                    <div class="clip2">
                                                        <div class="slice2"></div>
                                                    </div>
                                                    <div class="status"></div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div id="accordion" class="panel-group pabs">
                                            <div class="panel">
                                                <div class="panel-heading panel_game">
                                                    <h4 class="panel-title_popular">
                                                        <a href="#pabs_new-3" data-parent="#accordion" data-toggle="collapse">Место Проведения</a>
                                                    </h4>
                                                </div>
                                                <div id="pabs_new-3" class="panel-collapse collapse out">
                                                    <div class="panel-body">
                                                        <div class="link_box">
                                                           <div class="link_box_elements">
                                                                <div class="link_box_pab">
                                                                    <a href="#">Шульц</a>
                                                                </div>
                                                                <div class="link_box_marker">
                                                                    <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                                                                </div>
                                                            </div>
                                                                <hr>
                                                            <div class="link_box_elements">
                                                                <div class="link_box_pab">
                                                                    <a href="#">Siti Pab на Шулявке</a>
                                                                </div>
                                                                <div class="link_box_marker">
                                                                    <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                                                                </div>
                                                            </div>                      
                                                        </div>
                                                    </div>
                                                    <div class="panel-heading panel_game reverse">
                                                        <h4 class="panel-title_popular">
                                                            <a href="#">Подробнее</a>
                                                        </h4>
                                                    </div>
                                                 </div>
                                            </div>
                                        </div>      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="profileUser" tabindex="-1" role="dialog" aria-labelledby="profileUserLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="profileAdminLabel">Редактировать Профиль</h4>
                        </div>
                        <div class="modal-body">
                            <form id="frmUsers" name="frmTasks" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="login" class="col-sm-3 control-label">Логин</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="login_user" name="login" placeholder="Логин" value="" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Имя</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name_user" name="name" placeholder="Имя" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email_user" name="email" placeholder="Email" value="" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tel" class="col-sm-3 control-label">Телефон</label>
                                    <div class="col-sm-9">
                                        <input type="tel" class="form-control" id="tel_user" name="tel" placeholder="Номер телефона" value="" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-3 control-label">Пароль</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password_user" name="password" placeholder="Пароль" value="" pattern="[A-Za-z0-9_-]{4,8}" />
                                        <span id="msg"><b style="display: none" id="msg_ask_user">Сложность:</b> <i id="answer_user"></i></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save_user" value="add">Изменить</button>
                        </div>
                    </div>
                </div>
    </div>

    <div class="modal fade" id="CommandEdit" tabindex="-1" role="dialog" aria-labelledby="CommandEditLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="profileAdminLabel">Переименовать Комманду</h4>
                        </div>
                        <div class="modal-body">
                            <form id="frmCmnd" name="frmTasks" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="login" class="col-sm-3 control-label">Название</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="edit_command_name" name="edit_command_name" placeholder="Название команды" value="">
                                        <div class="edit_command_warning"></div>
                                    </div>
                                </div>
                                
                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-change-command" value="add">Изменить</button>
                        </div>
                    </div>
                </div>
    </div>

    <div class="modal fade" id="CommandDelete" tabindex="-1" role="dialog" aria-labelledby="CommandDeleteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="CommandDeleteLabel"></h4>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div style="display: flex; justify-content: space-around;">
                                    <button type="button" class="btn btn-danger" id="btn_delete_command">Удалить</button>
                                    <button type="button" class="btn btn-primary" id="btn_cancel_command" value="cancel">Отменить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>

    <div class="modal fade" id="show_command" tabindex="-1" role="dialog" aria-labelledby="PubManagerCreateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header clean_horizontal">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                    <div class="tabs">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#MyCommands_content" id="MyCommands" data-toggle="tab">Состав Команды</a></li>
                            <li><a href="#MyCommands_content_apply" id="MyCommandsApply" data-toggle="tab">Заявки в Команду</a></li>
                        </ul>
                        <div class="tab-content disign">
                            <div class="tab-pane fade in active" id="MyCommands_content">
                                <div class="container-fluid">
                                    <div class="commands">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="MyCommands_content_apply">
                                <div class="container-fluid">
                                    <div class="apply_commands">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="command_id">
    </div>

</main>
<input type="hidden" id="user_id" name="user_id" value="{{Auth::user()->id}}">
<script src="{{asset('assets/js/User.js')}}"></script>
<script src="{{asset('assets/js/CommandManager.js')}}"></script>
@endsection
