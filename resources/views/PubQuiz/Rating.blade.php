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



@section('content')
<main class="home_page admin">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-10 col-md-10">
                <ol id="breadcrumb">
                  <li>
                    <a href="{{url('/')}}"><i class="fas fa-home"></i>
                    <span class="sr-only">Главная</span></a>
                  </li>
                  <li class="current">
                    <a href="#">Рейтинг</a>
                  </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-3 col-md-3">
                <div class="rules_info">
                    <div id="accordion" class="panel-group">
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#rules_title" data-parent="#accordion" data-toggle="collapse">Правила начисления баллов</a>
                                </h4>
                            </div>
                            <div id="rules_title" class="panel-collapse collapse out">
                                <div class="panel-body">
                                    <table class="table rules_table">
                                        <thead>
                                            <tr>
                                                <th>Место</th>
                                                <th>Формула</th>
                                                <th>Итого</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1-40</td>
                                                <td>51-<занятое место></td>
                                                <td>баллов</td>
                                            </tr>
                                            <tr>
                                                <td>41-...</td>
                                                <td>10</td>
                                                <td>баллов</td>
                                            </tr>
                                            <tr>
                                                <td>Автор игры:</td>
                                                <td>30+3*<средняя оценка></td>
                                                <td>баллов</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7">
                <form class="rating_filters" action="{{ route('rating_result') }}" method="GET">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="Project" class="col-sm-3 control-label">Проект</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="Project" name="Project">
                                @if(!empty($projects))
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->Project_name }}</option>
                                    @endforeach
                                @else
                                    <option value="0" selected disabled>Проект не найден</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Season" class="col-sm-3 control-label">Сезон</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="Season" name="Season">
                                @if(!empty($seasons))
                                    @foreach($seasons as $season)
                                        <option value="{{ $season->id }}">{{ $season->Season_name }}</option>
                                    @endforeach
                                @else
                                    <option value="0" selected disabled>Сезон не найден</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-primary rating_filters_find">Поиск</button>
                </form>
            </div>
        </div>
    </div>
    
    @if($empty != true)
    <div class="container-fluid">
        <div class="row">
            <div class="global_rating_table">
                <div class="col-lg-1 col-md-1"></div>
                <div class="col-lg-3 col-md-3 none_padding">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Место</th>
                                <th>Команда</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($index_array as $key=>$value)
                            <?php 
                                $places = 1 + $key; 
                                $one_command = $command[$value];  
                                $one_command = explode('_', $one_command);  
                                $command_id = $one_command[0];  $command_name = $one_command[1];
                            ?>
                                @if(strpos($command_id, '#') == false)
                                    <tr class="command_{{ $command_id }}">
                                        <td>{{ $places }}</td>
                                        <td>{{ $command_name }}</td>
                                    </tr>
                                @else
                                    <tr style="" class="undefined">
                                        <td>{{ $places }}</td>
                                        <td>{{ $command_name }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-4 col-md-4 none_padding">
                    <div class="none_offset_rating">
                        <div class="search-table-outter wrapper" id="block">
                            <table class="table search-table inner" id="full_width">
                                <?php $first_load = true; $width = 0; ?>
                                @foreach($index_array as $key=>$value)
                                    <?php 
                                        $result_data = explode('=>', $result[$value]);
                                        $result_data = explode(';', $result_data[0]);
                                        $one_command = $command[$value];  
                                        $one_command = explode('_', $one_command);  
                                        $command_id = $one_command[0];  $command_name = $one_command[1];
                                    ?>
                                    @if($first_load == true)
                                        <thead class="wrap_table">
                                            <tr>
                                                @foreach($result_data as $data_value)
                                                    <?php
                                                        $width = count($result_data)*100 + 1;
                                                        $data_value = explode('->', $data_value);
                                                        $data_value = explode('(', $data_value[1]);
                                                        $first_load = false;
                                                    ?>
                                                    @if(count($result_data)<=4)
                                                     <th style="flex:auto;" class="game_id game_id{{ $data_value[0] }}">{{ $date_array[$data_value[0]] }}</th>
                                                    @else
                                                     <th  class="game_id game_id{{ $data_value[0] }}">{{ $date_array[$data_value[0]] }}</th>   
                                                    @endif
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="wrap_table">
                                            <tr>
                                                @foreach($result_data as $data_value)
                                                    <?php
                                                        $data_value = explode('(', $data_value);
                                                        $data_value = explode(')', $data_value[1]);
                                                        ?>
                                                    @if(count($result_data)<=4)
                                                        <td style="flex:auto;" class="command_{{ $command_id }}">{{ $data_value[0] }}</td>
                                                    @else
                                                        <td class="command_{{ $command_id }}">{{ $data_value[0] }}</td>
                                                    @endif
                                                    
                                                @endforeach
                                            </tr>   
                                    @else
                                        <tr>
                                            @foreach($result_data as $data_value)
                                                <?php
                                                    $data_value = explode('(', $data_value);
                                                    $data_value = explode(')', $data_value[1]);
                                                    ?>
                                                @if(count($result_data)<=4)
                                                        <td style="flex:auto;" class="command_{{ $command_id }}">{{ $data_value[0] }}</td>
                                                    @else
                                                        <td class="command_{{ $command_id }}">{{ $data_value[0] }}</td>
                                                    @endif
                                            @endforeach
                                        </tr>
                                    @endif
                               @endforeach
                               <script type="text/javascript">
                                $(function() {
                                    var b = document.getElementById("block");
                                        var w = b.clientWidth || b.offsetWidth;
                                        $('#w-scroll').css({width: w});

                                        $('#w-scroll .scroll').css({width: {{ $width }}});

                                    window.onresize = function(e){
                                        var b = document.getElementById("block");
                                        var w = b.clientWidth || b.offsetWidth;
                                        $('#w-scroll').css({width: w});

                                        var b_f = document.getElementById("full_width");
                                        console.log(b_f.offsetWidth);
                                        var w_f = b_f.clientWidth || b_f.offsetWidth;
                                        $('#w-scroll .scroll').css({width: w_f + 1});
                                    }
                                    $('#w-scroll').scroll(function(){
                                        $('.search-table-outter').scrollLeft($(this).scrollLeft());
                                        $(this).css({left: $(this).scrollLeft()});
                                    });
                                    $(window).scroll(function() {
                                        if($('.search-table').offset().top + $('.search-table').outerHeight() > $(window).height() + $(window).scrollTop()){
                                            $('#w-scroll').css({top: $(window).height() + $(window).scrollTop() - 28, bottom: 'auto'});
                                        }else{
                                            $('#w-scroll').css({top: 'auto', bottom: 0});
                                        };
                                    });
                                    $('#w-scroll').each(function() {
                                        if($('.search-table').offset().top + $('.search-table').outerHeight() > $(window).height() + $(window).scrollTop()){
                                            $(this).css({top: $(window).height() + $(window).scrollTop() - 28, bottom: 'auto'});
                                        }else{
                                            $(this).css({top: 'auto', bottom: 0});
                                        };
                                    });
                                });
                            </script>
                               </tbody>
                            </table>
                            <div class="footer_scoller">
                                <div id="w-scroll"><div class="scroll"></div></div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-lg-3 col-md-3 none_padding">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Итого</th>
                                <th>Без 2-х худших</th>
                                <th>Место</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($index_array as $key=>$value)
                            <?php 
                                $places = 1 + $key;

                                $one_command = $command[$value];  
                                $one_command = explode('_', $one_command);  
                                $command_id = $one_command[0];  $command_name = $one_command[1];

                                $one_result = $result[$value];
                                $one_result = explode('=>', $one_result);
                                $one_result = explode(':', $one_result[1]);
                                $total = $one_result[0];$mintotal = $one_result[1];
                            ?>
                                @if(strpos($command_id, '#') == false)
                                    <tr class="command_{{ $command_id }}">
                                        <td>{{ $total }}</td>
                                        <td>{{ $mintotal }}</td>
                                        <td>{{ $places }}</td>
                                        
                                    </tr>
                                @else
                                    <tr style="" class="undefined">
                                        <td>{{ $total }}</td>
                                        <td>{{ $mintotal }}</td>
                                        <td>{{ $places }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="container-fluid">
        <div class="row">
            <h1 style="text-align:center;margin: 140px 0 0;">Рейтинг Еще Не Сформирован</h1>
        </div>
    </div>
    @endif
        
</main>




@endsection
