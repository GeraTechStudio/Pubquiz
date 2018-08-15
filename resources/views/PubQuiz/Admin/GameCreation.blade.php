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
    <section class="scripts">
        <script src="{{asset('assets/libs/Autocmplete/jquery-ui.js')}}"></script>
        <script src="{{asset('assets/js/GameCreation/EditGame.js')}}"></script>
    </section>
    
@endsection


@section('header')
 {!!$region!!}
@endsection 

@section('navigation')
{!! $navigation  !!} 
@endsection

@section('header_content')  
@endsection

@section('hid')
@endsection



@section('content')
<main class="home_page admin">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-7 col-md-7">
                <ol id="breadcrumb">
                  <li>
                    <a href="{{url('/')}}"><i class="fas fa-home"></i>
                    <span class="sr-only">Главная</span></a>
                  </li>
                  <li>
                    <a href="{{ route('showAdminPage') }}">Личный Кабинет Администратора</a>
                  </li>
                  <li>
                    <a href="{{ route('ShowGame') }}">Календарь Игр</a>
                  </li>
                  <li class="current">
                    <a href="#">Создание Игры</a>
                  </li>
                </ol>
            </div>
            <div class="col-lg-3 col-md-3 right">
                <div class="btn-group btn-group-sm game_stages">
                    <button class="btn btn-info game_stage redact" value="0">Редактируется</button>
                    <button class="btn btn-warning game_stage ready" value="1">В игре</button>
                    <button class="btn btn-success game_stage finish" value="2">Окончена</button>
                    <button class="btn btn-danger delete_game">Удалить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="game">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <div class="game_title">                        
                        <h2>{{ $game->game_name}}</h2>
                            <div class="game_timer">
                                <i class="game_time">{{ $game->game_time}}</i>
                                <span><i> - </i></span>
                                <i class="game_date">{{ $game->game_date}}</i>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-3">
                    <div class="about_game">
                        <h3 class="game_type">Тип игры: <span style="color:{{ $game->project_color }}">{{ $game->project_name }}</span></h3>
                        <h3 class="season_type">{{ $game->season_name }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @if($game->confirmed == 2)
                @foreach ($locations as $key => $pub)
                        <div class="container-fluid">
                            <div class="row row_location_{{ $pub->id }}">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-3">
                                    <div class="game_locations" style="margin-right: 100px; margin-left:0">
                                    <?php 
                                        $reserve_table = explode('=>', $key);
                                        $pub_id = explode('[', $reserve_table[0]);
                                        $pub_id = $pub_id[1];
                                        $commands = explode(']', $reserve_table[1]);
                                        $commands = explode('->', $commands[0]);
                                        $all_tabels = $commands[0];
                                        $commands = explode('(', $commands[1]);$commands = explode(')', $commands[1]);$commands =$commands[0];
                                        $pub_command = $commands;
                                        if($commands == "None"){
                                            $commands = 0;
                                        }else{
                                            $commands = explode(' | ', $commands);
                                            $commands = count($commands);
                                        }

                                        $undefined_command = 0;

                                     ?>
                                    <div class="pub_box pub{{ $pub->id }}">
                                        <div class="pub_box_background" style="background-image: url({{ $pub->Location_img }});">
                                            <div class="full_screen"><button class="show_pub" id="show_pub{{ $pub->id }}" pub-command="{{ $pub_command }}" value="{{ $pub->id }}"><i class="fas fa-eye"></i></button></div>
                                            <div class="pub_box_manager_panel">
                                                <h2>{{ $pub->Location_name }}</h2>
                                            </div>
                                            <div class="pub_box_manager_type">
                                                <h2 id="pub_type{{ $pub->id }}">{{ $pub->Location_type }}</h2>
                                            </div>  
                                        </div>
                                        <div class="table_diagram" id="table_diagram{{ $pub->id }}">
                                            <div class="pie" >
                                                <div class="clip1">
                                                    <div class="slice1 slice{{ $pub_id }}1"></div>
                                                </div>
                                                <div class="clip2">
                                                    <div class="slice2 slice{{ $pub_id }}2"></div>
                                                </div>
                                                <div class="status" id="status{{ $pub_id }}">{{ $commands }}/{{ $all_tabels }}</div>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            progressBarUpdate({{ $commands }}, {{ $all_tabels }} ,"slice{{ $pub_id }}1","slice{{ $pub_id }}2");
                                        </script>
                                    </div>
                                    <form enctype="multipart/form-data" id="PubManager_img"  name="PubManager_img">
                                        <div class="file-upload" id="empty_zone">
                                            <label id="buf">
                                                <input type="file" class="rating" pub-id="{{ $pub->id }}">
                                                <span>Выберите файл</span>
                                            </label>
                                        </div>
                                    </form>
                                    @if($rating[$pub->id] != "Empty")
                                        @if($rating[$pub->id]->count_absent == 0)
                                            <button absent-command="{{ $rating[$pub->id]->count_absent }}" pub-id="{{ $pub->id }}" class="btn btn-success btn_present btn_absent{{ $pub->id }} active">Все команды найдены</button>
                                        @else
                                            <button absent-command="{{ $rating[$pub->id]->count_absent }}" pub-id="{{ $pub->id }}" class="btn btn-danger btn_absent btn_absent{{ $pub->id }}">Не найденные команды ({{ $rating[$pub->id]->count_absent }}шт.)</button>
                                        @endif
                                        
                                    
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="table_rating table_rating{{ $pub->id }}">

                                    @if($rating[$pub->id] == "Empty")
                                        <h1 class="rating_warning">Рейтинг еще не добавлен</h1>
                                    @else
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                     <th>Команда</th>
                                                     @for($i=1; $i <= $rating[$pub->id]->round_size; $i++)
                                                        <th>{{ $i }}</th>
                                                     @endfor
                                                     <th>Результат</th>
                                                     <th>Место</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <?php 
                                            $all_result = $rating[$pub->id]->game_data;
                                            $results = explode(',', $all_result);
                                            $places = 1;
                                            $buf_places = 1;
                                            $buffer_rating = 0;
                                        ?>
                                        @foreach($results as $result)
                                            <?php 
                                                $result = explode('[', $result);$result = explode(']', $result[1]);
                                                $result = explode('_', $result[0]);
                                                $command_id = $result[0];
                                                $result = explode('=>', $result[1]);$command_rating = $result[1];
                                                $result = explode('(', $result[0]);
                                                $command_name = $result[0];
                                                $command_raunds = explode(')', $result[1]);
                                                $command_raunds = $command_raunds[0];
                                                if($buffer_rating != $command_rating){
                                                    $places = $buf_places;
                                                    $buffer_rating = $command_rating;
                                                }
                                            ?>
                                            @if($command_raunds != "Author")
                                            <?php $command_raunds = explode(';',$command_raunds);?>
                                            @if($command_id == "?")
                                                <tr style="background-color: rgba(255, 0, 0, 0.08);">
                                                    <td id="undefined_{{ $undefined_command }}_{{ $pub->id }}">{{ $command_name }}</td>
                                            @else
                                                <tr>
                                                    <td id="command_{{ $command_id }}_{{ $pub->id }}">{{ $command_name }}</td>   
                                            @endif
                                                    @foreach($command_raunds as $command_raund)
                                                        <td>{{ $command_raund }}</td>
                                                    @endforeach
                                                    <td>{{ $command_rating }}</td>
                                                    <td>{{ $places }}</td> 
                                                <tr>
                                                <?php $buf_places++ ?> 
                                                <?php $undefined_command++; ?>
                                            @endif  
                                        @endforeach
                                    @endif
                                            </tbody>
                                        </table>
                                </div>
                                
                            </div>
                         </div>
                        </div>
                    @endforeach

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10 final_rating">
                                @if(!empty($final_rating))
                                    @if($final_rating->game_data != "None")
                                    <h1 class="common_rating">Общий Рейтинг за Игру</h1> 
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Команда</th>
                                                    @for($i=1; $i <= $final_rating->round_size; $i++)
                                                        <th>{{ $i }}</th>
                                                    @endfor
                                                    <th>Результат</th>
                                                    <th>Место</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <?php 
                                            $all_ratings = explode(',', $final_rating->game_data);
                                            $places = 1;
                                            $buf_places = 1;
                                            $buffer_rating = 0;
                                        ?>
                                        @foreach($all_ratings as $result)
                                            <?php
                                                $buf = $result;
                                                $result = explode('[', $result);$result = explode(']', $result[1]);
                                                $result = explode('_', $result[0]);
                                                $command_id = $result[0];
                                                $result = explode('=>', $result[1]);$command_rating = $result[1];
                                                $result = explode('(', $result[0]);
                                                $command_name = $result[0];
                                                $command_raunds = explode(')', $result[1]);
                                                $command_raunds = $command_raunds[0];
                                                if($buffer_rating != $command_rating){
                                                    $places = $buf_places;
                                                    $buffer_rating = $command_rating;
                                                }
                                            ?>
                                            @if($command_raunds == "Author")
                                                <tr style="background-color: #fce40f">
                                                    <td>{{ $command_name }}</td>
                                                    @for($i=1; $i <= $final_rating->round_size; $i++)
                                                        <td>Автор</td>
                                                    @endfor
                                                    <td>{{ $command_rating }}</td>
                                                    <td>{{ $places }}</td>
                                                    <?php $buf_places++; ?>
                                                </tr>
                                            @else
                                                <?php $pub_color = explode('->', $buf);$pub_color = $pub_color[1]; ?>
                                                @if($command_id == "?")
                                                    <tr style="background-color: rgba(255, 0, 0, 0.08);">
                                                @else
                                                    <tr style="background-color:{{ $pub_color }}">
                                                @endif
                                                    <td>{{ $command_name }}</td>
                                                    <?php $command_raunds = explode(';', $command_raunds) ?>
                                                        @foreach($command_raunds as $command_raund)
                                                            <td>{{ $command_raund }}</td>
                                                        @endforeach
                                                    
                                                    <td>{{ $command_rating }}</td>
                                                    <td>{{ $places }}</td>
                                                    <?php $buf_places++; ?>


                                                </tr>
                                            @endif
                                        @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                      <h1 class="common_rating">Общий Рейтинг за Игру еще не загружен</h1> 
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>                                  
                @endif       
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="EditRating" tabindex="-1" role="dialog" aria-labelledby="EditRatingLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="profileAdminLabel">Не найденные Команды</h4>
                        </div>
                        <div class="modal-body">
                            <form id="frmRating" name="frmRating" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="login" class="col-sm-6 control-label" style="text-align: left;">Команда</label>
                                    <div class="col-sm-6">
                                        <i class="fas fa-times-circle not_found"></i>
                                    </div>
                                </div>
                                <div class="form-group error">
                                    <label for="login" class="col-sm-6 control-label" style="text-align: left;">Команда</label>
                                    <div class="col-sm-6">
                                        <i class="fas fa-times-circle not_found"></i>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close">Закрыть</button>
                            <input type="hidden" id="modal_pub_id" value="">
                        </div>
                    </div>
                </div>
        </div>




    <div class="modal fade" id="DeleteGame" tabindex="-1" role="dialog" aria-labelledby="DeleteGame" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">{{Auth::user()->name}}, Вы уверены что хотите удалить Игру <span id="DeleteTitle"></span>?</h4>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div style="display: flex; justify-content: space-around;">
                                    <button type="button" class="btn btn-danger" id="delete" value="">Удалить</button>
                                    <button type="button" class="btn btn-primary" id="cancel" value="cancel">Отменить</button>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    </div> 

    <div class="modal fade" id="ShowPub" tabindex="-1" role="dialog" aria-labelledby="PubManagerCreateLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header clean_horizontal">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <div class="tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#Pub_Info" id="PubName" data-toggle="tab"></a></li>
                                    <li><a href="#Pub__content_map" id="PubMap" data-toggle="tab"></a></li>
                                    <li><a href="#Pub_command" id="PubCommand" data-toggle="tab"></a></li>
                                </ul>
                                <div class="tab-content disign">
                                    <div class="tab-pane fade in active" id="Pub_Info">
                                        <div class="admin_table">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="pub_info_content">
                                                        <div class="col-sm-4">
                                                            <div class="pub_img_box">
                                                            </div>
                                                            <i id="pub_info_content_address"></i>
                                                        </div>
                                                    

                                                        <div class="col-sm-8">   
                                                            <h1 id="pub_info_content_type"></h1>
                                                            <div id="pub_desc"></div>
                                                        </div> 
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Pub__content_map">
                                        <div id="map_url">
                                            
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Pub_command">
                                        <div id="list_commands">
                                            
                                        </div>
                                        <div class="add_command">
                                            <div style='display: flex;justify-content: space-around;align-items: center;margin-top:15px;'>
                                                <input type='name' autocomplete='off' style='margin-top:6px;' class="form-control has-error add_command_input"  placeholder='Поиск Команды'><button style="margin-top: 5px;" class="btn btn-success add_command-btn">Добавить</button></div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <input type="hidden" id="shown_pub_id" value="0">
    </div>

</main>

<input type="hidden" id="id_game" value="{{$game->id}}">
<input type="hidden" id="game_stage" value="{{$game->confirmed}}">
<input type="hidden" id="asset" value="{{asset('assets/js/GameCreation')}}">








@endsection
