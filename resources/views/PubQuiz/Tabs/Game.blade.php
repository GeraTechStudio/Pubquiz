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
    <script type="text/javascript">
        function rotate(element, degree) {
            element.css({
            '-webkit-transform': 'rotate(' + degree + 'deg)',
                '-moz-transform': 'rotate(' + degree + 'deg)',
                '-ms-transform': 'rotate(' + degree + 'deg)',
                '-o-transform': 'rotate(' + degree + 'deg)',
                'transform': 'rotate(' + degree + 'deg)',
                'zoom': 1
            });
        }

                function progressBarUpdate(x, outOf, class_one, class_two) {
                var firstHalfAngle = 180;
                var secondHalfAngle = 0;

                // caluclate the angle
                var drawAngle = x / outOf * 360;

                // calculate the angle to be displayed if each half
                if (drawAngle <= 180) {
                    firstHalfAngle = drawAngle;
                } else {
                    secondHalfAngle = drawAngle - 180;
                }

                // set the transition
                var class1 = "."+ class_one;
                var class2 = "."+ class_two;
                rotate($(class1), firstHalfAngle);
                rotate($(class2), secondHalfAngle);

                }                                               
    </script>
    
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
<main class="home_page admin">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-10 col-md-10 hidden-xs">
                <ol id="breadcrumb">
                  <li>
                    <a href="{{url('/')}}"><i class="fas fa-home"></i>
                    <span class="sr-only">Главная</span></a>
                  </li>
                  <li>
                    <a href="{{ route('GameCalendar') }}">Календарь Игр</a>
                  </li>
                  <li class="current">
                    <a href="#">{{ $game->game_name}}</a>
                  </li>
                </ol>
            </div>
        </div>
    </div>
    @if($game->confirmed == 1)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 hidden-xs">
                    <div class="game_title">                        
                        <h2>{{ $game->game_name}}</h2>
                            <div class="game_timer">
                                <i class="game_time">{{ $game->game_time}}</i>
                                <span><i> - </i></span>
                                <i class="game_date">{{ $game->game_date}}</i>
                            </div>
                    </div>
                </div> 
                <div class="visible-xs col-xs-12">
                    <div class="col-xs-12">
                        <div class="game_title">                        
                                <h2>{{ $game->game_name}}</h2>
                        </div>
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
                <div class="col-lg-offset-1 col-md-offset-1 col-lg-7 col-md-7">
                    <div class="game_content">
                        <div class="col-md-5 col-sm-6">
                            <div class="game_img_content">
                                <div class="eTimer game_timer_show"></div>
                                
                            
                                <div class="game_img_box game_img_box_none_border game_margin">
                                    <img src="{{ $game->game_img_path }}">
                                </div> 
                            </div>

                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-6 col-sm-6">
                            <div class="about_game">
                                <h3 class="game_type">Тип игры: <span style="color:{{ $game->project_color }}">{{ $game->project_name }}</span></h3>
                                <h3 class="season_type">{{ $game->season_name }}</h3>
                                <div class="game_desc">
                                    {!! $game->game_desc !!}
                                </div>
                            </div>   
                            
                        </div>
                     
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="game_locations">
                        @foreach ($locations as $key => $pub)
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

                                 ?>
                            <div class="pub_box pub{{ $pub->id }}">
                                <div class="pub_box_background" style="background-image: url({{ $pub->Location_img }});">
                                    <div class="full_screen"><button class="show_pub" id="show_pub{{ $pub->id }}" pub-command="{{ $pub_command }}" value="{{ $pub->id }}"><i class="fas fa-eye"></i></button></div>
                                    <div class="pub_box_manager_panel">
                                        <h2>{{ $pub->Location_name }}</h2>
                                        <div class="manager_button manager_button{{ $pub->id }}">
                                            @if($commands == $all_tabels)
                                            <button class="btn btn-default disabled" value="{{ $pub->id }}">Подать Заявку</button>
                                            @else
                                            <button class="btn btn-success apply_command" value="{{ $pub->id }}">Подать Заявку</button>
                                            @endif        
                                            
                                        </div>
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($game->confirmed == 2)
    <div class="game">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="game_title">                        
                        <h2>{{ $game->game_name}}</h2>
                            <div class="game_timer">
                                <i class="game_time">{{ $game->game_time}}</i>
                                <span><i>@</i></span>
                                <i class="game_date">{{ $game->game_date}}</i>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-3">
                    <div class="about_game">
                        <h3 class="game_type">Тип игры: <span style="color:{{ $game->project_color }}">{{ $game->project_name }}</span></h3>
                        <h3 class="season_type">{{ $game->season_name }}</h3>
                    </div>
                </div>
            </div>
        </div>
                @foreach ($locations as $key => $pub)
                        <div class="container-fluid">
                            <div class="row row_location_{{ $pub->id }}">
                                <div class="col-md-1"></div>
                                <div class="col-md-3">
                                    <div class="game_locations">
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
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="table_rating table_rating{{ $pub->id }}">

                                    @if($rating[$pub->id] == "Empty")
                                        <h1 class="rating_warning">Рейтинг еще не добавлен</h1>
                                    @else
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                     <th class="first_column_rating">Команда</th>
                                                     @for($i=1; $i <= $rating[$pub->id]->round_size; $i++)
                                                        <th class="hidden-xs">{{ $i }}</th>
                                                     @endfor
                                                     <th class="second_column_rating">Результат</th>
                                                     <th class="theard_column_rating">Место</th>
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
                                                <tr style="background-color: red">
                                                    <td class="first_column_rating" id="undefined_{{ $undefined_command }}_{{ $pub->id }}">{{ $command_name }}</td>
                                            @else
                                                <tr>
                                                    <td class="first_column_rating" id="command_{{ $command_id }}_{{ $pub->id }}">{{ $command_name }}</td>   
                                            @endif
                                                    @foreach($command_raunds as $command_raund)
                                                        <td class="hidden-xs">{{ $command_raund }}</td>
                                                    @endforeach
                                                    <td class="second_column_rating">{{ $command_rating }}</td>
                                                    <td class="theard_column_rating">{{ $places }}</td> 
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
                            <div class="col-md-1"></div>
                            <div class="col-md-10 final_rating">
                                @if(!empty($final_rating))
                                    @if($final_rating->game_data != "None")
                                    <h1 class="common_rating">Общий Рейтинг за Игру</h1> 
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="first_column_rating">Команда</th>
                                                    @for($i=1; $i <= $final_rating->round_size; $i++)
                                                        <th class="hidden-xs">{{ $i }}</th>
                                                    @endfor
                                                    <th class="second_column_rating">Результат</th>
                                                    <th class="theard_column_rating">Место</th>
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
                                                    <td class="first_column_rating">{{ $command_name }}</td>
                                                    @for($i=1; $i <= $final_rating->round_size; $i++)
                                                        <td class="hidden-xs">Автор</td>
                                                    @endfor
                                                    <td class="second_column_rating">{{ $command_rating }}</td>
                                                    <td class="theard_column_rating">{{ $places }}</td>
                                                    <?php $buf_places++; ?>
                                                </tr>
                                            @else
                                                <?php $pub_color = explode('->', $buf);$pub_color = $pub_color[1]; ?>
                                                @if($command_id == "?")
                                                    <tr style="background-color: red">
                                                @else
                                                    <tr style="background-color:{{ $pub_color }}">
                                                @endif
                                                    <td class="first_column_rating">{{ $command_name }}</td>
                                                    <?php $command_raunds = explode(';', $command_raunds) ?>
                                                        @foreach($command_raunds as $command_raund)
                                                            <td class="hidden-xs">{{ $command_raund }}</td>
                                                        @endforeach
                                                    
                                                    <td class="second_column_rating">{{ $command_rating }}</td>
                                                    <td class="theard_column_rating">{{ $places }}</td>
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
            </div>
        </div>
    </div>
    @endif 



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
                                                        <div class="col-md-4">
                                                            <div class="pub_img_box">
                                                            </div>
                                                            <i id="pub_info_content_address"></i>
                                                        </div>
                                                    

                                                        <div class="col-md-8">   
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <input type="hidden" id="shown_pub_id" value="0">
    </div>

    <div class="modal fade" id="addCommand" tabindex="-1" role="dialog" aria-labelledby="addCommandLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="addCommand">Подать заявку на игру</h4>
                        </div>
                        <div class="modal-body">
                            <div class="addCommand_content">
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary add_command_to_game" value="add">Подать Заявку</button>
                            <input type="hidden" id="command_add_pub_id" value="0">
                        </div>
                    </div>
                </div>
        </div>
</main>

<?php 
    $time = $game->game_time;
    $time = explode(':', $time);
    
    $date = $game->game_date;
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

<script src="{{asset('assets/js/ShowGame/GameManager.js')}}"></script>
<input type="hidden" id="id_game" value="{{ $game->id }}">
 

@endsection
