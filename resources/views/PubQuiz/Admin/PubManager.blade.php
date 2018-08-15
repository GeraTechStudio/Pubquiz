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
        body .ui-autocomplete{
            position: absolute;
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
                    <a href="#">Управление Локациями</a>
                  </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-3 col-md-3">
                <div class="admin_panel">
                    <div id="accordion" class="panel-group">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#admin_panel" data-parent="#accordion" data-toggle="collapse">Панель Администратора</a>
                                    </h4>
                                </div>
                                <div id="admin_panel" class="panel-collapse collapse out">
                                    <div class="panel-body">
                                        <div class="link_box">
                                            <a href="{{ route('ShowManagerPub') }}">Управление Локациями</a>
                                            <a href="{{ route('ShowGame') }}">Игры</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                
                <div class="projects" style="margin-bottom:10px ">
                    <div id="accordion" class="panel-group">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#Type_of_Pub" data-parent="#accordion" data-toggle="collapse">Типы Локаций</a>
                                    </h4>
                                </div>
                                <div id="Type_of_Pub" class="panel-collapse collapse out">
                                    <div class="panel-body">
                                        <table class="table">
                                          <thead>
                                            <tr>
                                              <th scope="col">Название</th>
                                              <th scope="col" class="center">Управление</th>
                                            </tr>
                                          </thead>
                                          <tbody id="types-list" name="types-list">
                                            @foreach($types as $type)
                                                <tr id="type{{ $type->id }}">
                                                  <th scope="row">{{ $type->Type_name }}</th>
                                                  <td class="center">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-warning type_edit" value="{{ $type->id }}"><i class="fas fa-cog"></i></button>
                                                        <button class="btn btn-danger type_delete" value="{{ $type->id }}"><i class="fas fa-trash-alt"></i></button>
                                                    </div>
                                                   </td>
                                                </tr>
                                            @endforeach
                                          </tbody>
                                        </table>
                                            <div class="sercher_block sercher_block_padding ">
                                                <form id="frmType" name="frmType" novalidate="">
                                                    <input type="text" class="form-control" id="type_name" name="type_name" placeholder="Тип Локации" value="">
                                                </form>
                                                <button class="btn btn-primary create_type" value="create">Создать</button>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <div class="home_game__one_box">
                        <div class="home_game_one_box_img empty_box">
                            <div class="add_comand_game">
                                <div class="add_command_round"><span><button type="button" id="btn_create_Pub" value="create">+</button></span></div>
                                <div class="add_command_button">Создать Локацию</div>
                            </div>
                        </div>
                </div>

                <div class="pub_filter">
                    <h3>Поиск по локациям</h3>
                        <div class="sercher_block">
                            <input type="text" class="form-control" id="searcher" name="searcher" placeholder="Поиск:" value="">
                            <button class="btn btn-primary search" value=""><i class="fas fa-search"></i></button>
                        </div>
                </div>
            </div>

            <div class="col-lg-7 col-md-7">
                <div class="home_content_page">
                    <div class="content_welcome welcome_location">
                        <h1>Ваши Локации <button class="update_pubs" value=""><i class="fas fa-sync-alt"></i></button></h1>
                    </div>
                    <div class="pubs_box">
                        <div class="container-fluid">
                            <div class="row" id="add_pubs">
                            
                            @if(count($Pubs) != 0)
                                @foreach($Pubs as $pub)
                                <div class="col-md-4">
                                    <div class="pub_box pub{{ $pub->id }}">
                                        <div class="pub_box_background" style="background-image: url({{ $pub->Location_img }});">
                                            <div class="full_screen"><button class="show_pub" value="{{ $pub->id }}"><i class="fas fa-eye"></i></button></div>
                                            <div class="pub_box_manager_panel">
                                                <h2>{{ $pub->Location_name }}</h2>
                                                <div class="manager_button">
                                                    <button class="btn btn-warning edit-pub" value="{{ $pub->id }}">Редактировать <i class="fas fa-cog"></i></button>
                                                    <button class="btn btn-danger delete-pub" value="{{ $pub->id }}">Удалить <i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                            <div class="pub_box_manager_type">
                                                <h2 id="pub_type{{ $pub->id }}">{{ $pub->Location_type }}</h2>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <h2 id="warning" >Вы еще не создали Локацию</h2>  
                            @endif 
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="PubManagerCreate" tabindex="-1" role="dialog" aria-labelledby="PubManagerCreateLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="PubManagerCreateLabel">Создать Локацию</h4>
                        </div>
                        <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row">
                                       <div class="col-sm-4">
                                            <div class="img_box">
                                                <div class="size">[250x300]</div>
                                            </div>
                                            <form enctype="multipart/form-data" id="PubManager_img"  name="PubManager_img">
                                                <div class="file-upload" id="empty_zone">
                                                    <label id="buf">
                                                         <input type="file" id="logo_pub" name="imgPub" accept="image/*">
                                                         <span>Выберите файл</span>
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-8">   
                                         <form id="PubManager" name="PubManager" class="form-horizontal" novalidate="">                                          
                                            <div class="form-group error">
                                                <label for="Location_name" class="col-sm-3 control-label">Название</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control has-error" id="Location_name" name="location_name" placeholder="Назовите Локацию" value="">
                                                </div>
                                            </div>

                                            <div class="form-group error">
                                                <label for="Location_address" class="col-sm-3 control-label">Адрес</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control has-error" id="Location_address" name="location_address" placeholder="Адрес Локации" value="">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group error">
                                                <label for="Location_map" class="col-sm-3 control-label">Карта</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control has-error" id="Location_map" name="Location_map" placeholder="Ссылка на карту Google" value="" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group error">
                                                <label for="Location_color" class="col-sm-3 control-label">Цвет</label>
                                                <div class="col-sm-9">
                                                    <input type="color"  id="pub_color" value="" name="pub_color">
                                                </div>
                                            </div>

                                            <div class="form-group error">
                                                <label for="Location_type" class="col-sm-3 control-label">Тип</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control has-error" id="Location_type" name="Location_type">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group error">
                                                <label for="Location_description" class="col-sm-3 control-label">Описание</label>
                                                <div class="col-sm-9">
                                                    <textarea id="Location_description" name="Location_description" class="form-control has-error module_textarea" placeholder="Описание Локации:"></textarea>
                                                </div>
                                            </div>

                                         </form>
                                        </div> 
                                    </div>
                                </div>
                                  
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn_create_pub" value="">Создать</button>
                            <input type="hidden" id="pub_img" name="pub_img" value="empty">
                            <input type="hidden" id="pub_img_path" name="pub_img_path" value="empty">
                            <input type="hidden" id="Pub_id" name="pub_id" value="empty">
                            <input type='hidden' id='buffer' name='buffer' value='empty'>
                        </div>
                    </div>
                </div>
        </div> 

        <div class="modal fade" id="Delete_pub" tabindex="-1" role="dialog" aria-labelledby="Delete_Pub" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="Delete_pub_title">{{Auth::user()->name}}, <span id="Delete_pub_ask_admin">Вы уверены что хотите удалить Локацию?</span></h4>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div style="display: flex; justify-content: space-around;">
                                    <button type="button" class="btn btn-danger" id="btn_delete" value="">Удалить</button>
                                    <button type="button" class="btn btn-primary" id="btn_cancel" value="cancel">Отменить</button>
                                    <input type="hidden" id="Pub_delete_id" name="Pub_delete_id" value="0">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>  

</main>

<script src="{{asset('assets/js/PubManager.js')}}"></script>
<script src="{{asset('assets/js/TypePubManager.js')}}"></script>
<script src="{{asset('assets/js/MainPubManager.js')}}"></script>
<script src="{{asset('assets/libs/Autocmplete/jquery-ui.js')}}"></script>
@endsection
