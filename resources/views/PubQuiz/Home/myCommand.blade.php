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
{!! $navigation !!}     
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
                  <li>
                    <a href="{{ route('home') }}">Личный Кабинет</a>
                  </li>
                  <li class="current">
                    <a href="#">Команда {{ $command->command_name }}</a>
                  </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h1 class="myGameTitle">Игры Команды {{ $command->command_name }}</h1>
                <div class="row">
                    @if($games == "None")
                    <h1 class="absent_game">Команда {{ $command->command_name }} не состоит ни в одной игре!</h1>
                    @else
                    <div class="content_popular">
                        @foreach($games as $game)
                                <div class="col-sm-3">
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
                                                    <div class="status">{{ $game->disable_table }}/{{ $game->all_table }}</div>
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
                    </div>
                    @endif
                </div>
            </div>   
        </div>
    </div>
     
    <div class="modal fade" id="ShowPub" tabindex="-1" role="dialog" aria-labelledby="PubManagerCreateLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="PubName"></h4>
                        </div>
                        <div class="modal-body">
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
                </div>
    </div>

    <div class="modal fade" id="ShowPubMap" tabindex="-1" role="dialog" aria-labelledby="PubManagerCreateLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="PubMap"></h4>
                        </div>
                        <div class="modal-body">
                            <div id="map_url"></div>
                        </div>
                    </div>
                </div>
    </div>
    
</main>

<script src="{{asset('assets/js/myGame.js')}}"></script>
@endsection
