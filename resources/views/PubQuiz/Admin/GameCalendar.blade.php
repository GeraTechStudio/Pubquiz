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
        .cl-game{
          top: 70px!important;
        }
    </style>
    
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
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-10 col-md-10">
                <ol id="breadcrumb">
                  <li>
                    <a href="{{url('/')}}"><i class="fas fa-home"></i>
                    <span class="sr-only">Главная</span></a>
                  </li>
                  <li>
                    <a href="{{ route('showAdminPage') }}">Личный Кабинет Администратора</a>
                  </li>
                  <li class="current">
                    <a href="#">Календарь Игр</a>
                  </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-10 col-md-10">
                <div id='calendar'></div>
            </div>

        </div>
    </div>
      
    <div class="modal fade" id="Create_game" tabindex="-1" role="dialog" aria-labelledby="Create_game" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
               <h4 class="modal-title" id="Create_game_title">Создать Игру</h4>
             </div>
             <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-sm-10">   
                                         <form id="Game_creation" name="Game_creation" class="form-horizontal" novalidate="">

                                            <div class="form-group error">
                                                <label for="Game_name" class="col-sm-3 control-label">Название</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control has-error" id="Game_name" name="Game_name" placeholder="Название Игры" value="">
                                                </div>
                                            </div>

                                            <div class="form-group error">
                                                <label for="Project_Selection" class="col-sm-3 control-label">Проект</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control has-error" id="Project_Selection" name="Project_Selection">
                                                    </select>
                                                </div>
                                            </div>

                                             <div class="form-group error">
                                                <label for="Season_selection" class="col-sm-3 control-label">Сезон</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control has-error" id="Season_selection" name="Season_selection">
                                                    </select>
                                                </div>
                                            </div>
                                         </form>
                                        </div> 
                                    </div>
                                </div>   
                            </div>
                <div class="modal-footer">
                     <button type="button" class="btn btn-primary" id="btn_create_game" value="create">Создать</button>
                     <input type="hidden" name="game_date" id="game_date" value="">
                     <input type="hidden" name="url" id="url" value="{{url('/')}}">
                </div>
            </div>
          </div>
      </div>
    </div>  
</main>
<script src="{{asset('assets/libs/fullcalendar/js/moment.min.js')}}"></script>
<script src="{{asset('assets/libs/fullcalendar/js/fullcalendar.min.js')}}"></script>
<script src="{{asset('assets/libs/fullcalendar/locale/ru.js')}}"></script>
<script src="{{asset('assets/js/GameManager.js')}}"></script>
 

@endsection
