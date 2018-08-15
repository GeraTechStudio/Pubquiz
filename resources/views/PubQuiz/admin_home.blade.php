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
    <script src="{{asset('assets/libs/Autocmplete/jquery-ui.js')}}"></script>
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
                  <li class="current">
                    <a href="#">Личный Кабинет Администратора</a>
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
                
                <div class="projects">
                    <div id="accordion" class="panel-group">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#project_panel" data-parent="#accordion" data-toggle="collapse">Ваши Проекты</a>
                                    </h4>
                                </div>
                                <div id="project_panel" class="panel-collapse collapse out">
                                    <div class="panel-body">
                                        <table class="table">
                                          <thead>
                                            <tr>
                                              <th scope="col">Название</th>
                                              <th scope="col" class="center">Управление</th>
                                            </tr>
                                          </thead>
                                          <tbody id="projects-list" name="projects-list">
                                            @foreach($projects as $project)
                                                <tr id="project{{ $project->id }}">
                                                  <th scope="row" class="project_inline" id="project_name{{ $project->id }}">{{ $project->Project_name }}<div class="mini_box_color" style="background-color: {{ $project->Project_color }} "></div></th>
                                                  <td class="center">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-warning project_edit" value="{{ $project->id }}"><i class="fas fa-cog"></i></button>
                                                        <button class="btn btn-danger project_delete" value="{{ $project->id }}"><i class="fas fa-trash-alt"></i></button>
                                                    </div>
                                                   </td>
                                                </tr>
                                            @endforeach
                                          </tbody>
                                        </table>
                                            <div class="sercher_block sercher_block_padding ">
                                                <form id="frmProject" name="frmProject" novalidate="">
                                                    <input type="text" class="form-control" id="project_name" name="project_name" placeholder="Название проекта" value="">
                                                    <input type="color"  id="projet_color" value="#3f95a4" name="projet_color">
                                                </form>
                                                <button class="btn btn-primary create_project" value="create">Создать</button>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <div class="seasons">
                    <div id="accordion" class="panel-group">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#seasons_panel" data-parent="#accordion" data-toggle="collapse">Ваши Сезоны</a>
                                    </h4>
                                </div>
                                <div id="seasons_panel" class="panel-collapse collapse out">
                                    <div class="panel-body">
                                        <table class="table">
                                          <thead>
                                            <tr>
                                              <th scope="col">Название</th>
                                              <th scope="col" class="center">Управление</th>
                                            </tr>
                                          </thead>
                                          <tbody id="seasons-list" name="seasons-list">
                                             @foreach($seasons as $season)
                                                <tr id="season{{ $season->id }}">
                                                  <th scope="row" id="project_name{{ $season->id }}">{{ $season->Season_name }}</th>
                                                  <td class="center">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-warning season_edit" value="{{ $season->id }}"><i class="fas fa-cog"></i></button>
                                                        <button class="btn btn-danger season_delete" value="{{ $season->id }}"><i class="fas fa-trash-alt"></i></button>
                                                    </div>
                                                   </td>
                                                </tr>
                                              @endforeach
                                          </tbody>
                                        </table>
                                            <div class="sercher_block sercher_block_padding ">
                                                <form id="frmSeason" name="frmSeason" novalidate="">
                                                    <input type="text" class="form-control" id="season_name" name="season_name" placeholder="Название Сезона" value="">
                                                </form>
                                                <button class="btn btn-primary create_season" value="create">Создать</button>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-7 col-md-7">
                <div class="home_content_page">
                    <div class="content_welcome">
                        <h1 id="admin">Здравствуйте, Администратор, {{Auth::user()->name}}! <button class="transparent open-modal" value="{{Auth::user()->id}}"><i class="fas fa-cog"></i></button></h1>

                    </div>
                    
                    <div class="tabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#admins" id="adm" data-toggle="tab">Администраторы</a></li>
                        <li><a href="#users" id="usr" data-toggle="tab">Пользователи</a></li>
                        <li><a href="#commands" id="cmd" data-toggle="tab">Команды</a></li>
                        <li><a href="#oders" id="cmd" data-toggle="tab">Заказы</a></li>
                    </ul>
                        <div class="tab-content disign">
                            <div class="tab-pane fade in active" id="admins">
                                <div class="admin_table">
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th scope="col">ID</th>
                                          <th scope="col" class="column_login">Логин</th>
                                          <th scope="col" class="column_name">Имя</th>
                                          <th scope="col">Телефон</th>
                                          <th scope="col" class="column_verified">Подтвержден</th>
                                          <th scope="col">Действие</th>
                                        </tr>
                                      </thead>
                                      <tbody id="admins-list" name="admins-list">
                                        @foreach($admins as $admin)
                                            @if(Auth::user()->login == $admin->login)
                                            <tr id="admin{{$admin->id}}">
                                              <th scope="row">{{$admin->id}}</th>
                                              <td class="column_login" title="{{$admin->login}}">{{$admin->login}}</td>
                                              <td class="column_name" title="{{$admin->name}}">{{$admin->name}}</td>
                                              <td>{{$admin->tel}}</td>
                                              <td class="column_verified">{{$admin->verified}}</td>
                                              <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-default disabled" value="{{$admin->id}}"><i class="fas fa-lock-open"></i></button>
                                                        <button class="btn btn-warning disabled" value="{{$admin->id}}"><i class="fas fa-cog"></i></button>
                                                        <button class="btn btn-danger disabled" value="{{$admin->id}}"><i class="fas fa-trash-alt"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @else
                                                <tr id="admin{{$admin->id}}">
                                                  <th scope="row">{{$admin->id}}</th>
                                                  <td class="column_login">{{$admin->login}}</td>
                                                  <td class="column_name">{{$admin->name}}</td>
                                                  <td>{{$admin->tel}}</td>
                                                  <td class="column_verified">{{$admin->verified}}</td>
                                                  <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-default change-status" value="{{$admin->id}}"><i class="fas fa-lock-open"></i></button>
                                                            <button class="btn btn-warning open-modal" value="{{$admin->id}}"><i class="fas fa-cog"></i></button>
                                                            <button class="btn btn-danger delete-admin" value="{{$admin->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="users">
                                <div class="user_table">
                                    <div class="toolbar" toggle="asc">
                                      <div class="toolbar_order">
                                        <div class="toolbar-btn toolbar-order_page toolbar-order_page-up toolbar-btn-allow" toggle="desc" status="disabled" toolbar="User"><i class="fas fa-long-arrow-alt-up"></i></div>
                                        <div class="toolbar-btn toolbar-order_page toolbar-order_page-down toolbar-btn-active" toggle="asc" status="active" toolbar="User"><i class="fas fa-long-arrow-alt-down"></i></div>
                                      </div>
                                      <div class="toolbar_manage-page">
                                        <!-- <div class="toolbar-btn toolbar-first_page toolbar-btn-disabled" status="disabled" toolbar="User"><i class="fas fa-angle-double-left"></i></div>
                                        <div class="toolbar-btn toolbar-prev_page toolbar-btn-disabled" status="disabled" toolbar="User"><i class="fas fa-angle-left"></i></div> -->
                                        <label for="user_pages"># Страницы</label>
                                          <select class="choose_toolbar choose_page" toolbar="User" id="user_pages">
                                            @for($i=1; $i<=$array_page['user_pages']; $i++)
                                              <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                          </select>
                                       <!--  <div class="toolbar-btn toolbar-next_page toolbar-btn-allow" status="allow" toolbar="User"><i class="fas fa-angle-right"></i></div>
                                        <div class="toolbar-btn toolbar-last_page toolbar-btn-allow" status="allow" toolbar="User"><i class="fas fa-angle-double-right"></i></div> -->
                                      </div>
                                      <div class="toolbar_manage_items">
                                        <label for="count_pages">Кол-во:</label>
                                          <select class="choose_toolbar choose_items" toolbar="User" id="count_pages">
                                            <option value="25" selected>25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="250">250</option>
                                            <option value="500">500</option>
                                          </select>
                                      </div>
                                      <div class="toolbar_searcher_table">
                                        <input type="name" class="searcher_admin_pannel" placeholder="Поиск по таблице" toolbar="User">
                                      </div>
                                    </div> 
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th scope="col">ID</th>
                                          <th scope="col" class="column_login">Логин</th>
                                          <th scope="col" class="column_name">Имя</th>
                                          <th scope="col">Телефон</th>
                                          <th scope="col" class="column_verified">Подтвержден</th>
                                          <th scope="col">Действие</th>
                                        </tr>
                                      </thead>
                                      <tbody id="users-list" name="users-list">
                                        @foreach($users as $user)
                                                <tr id="user{{$user->id}}">
                                                  <th scope="row">{{$user->id}}</th>
                                                  <td class="column_login" title="{{$user->login}}">{{$user->login}}</td>
                                                  <td class="column_name" title="{{$user->name}}">{{$user->name}}</td>
                                                  <td>{{$user->tel}}</td>
                                                  <td class="column_verified">{{$user->verified}}</td>
                                                  <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-primary change-status" value="{{$user->id}}"><i class="fas fa-lock"></i>
                                                            <button class="btn btn-warning open-modal" value="{{$user->id}}"><i class="fas fa-cog"></i></button>
                                                            <button class="btn btn-danger delete-admin" value="{{$user->id}}"><i class="fas fa-trash-alt"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="commands">
                                <div class="user_table">
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th scope="col">ID</th>
                                          <th scope="col" class="column_login">ID User</th>
                                          <th scope="col" class="column_name">Название</th>
                                        </tr>
                                      </thead>
                                      <tbody id="command-list" name="command-list">
                                        @foreach($commands as $command)
                                                <tr id="command{{$command->id}}">
                                                  <th scope="row">{{$command->id}}</th>
                                                  <td class="column_login" title="{{$command->login}}">{{$command->id_user}}</td>
                                                  <td class="column_name" title="{{$command->command_name}}">{{$command->command_name}}</td>
                                                </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="oders">
                                <div class="user_table">
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th scope="col">ID</th>
                                          <th scope="col" class="column_login">Email</th>
                                          <th scope="col" class="column_name">Имя</th>
                                          <th scope="col" class="column_name">Телефон</th>
                                          <th scope="col" class="column_name">Время</th>
                                        </tr>
                                      </thead>
                                      <tbody id="corporates-list" name="corporates-list">
                                        @foreach($corporates as $corporates)
                                                <tr id="corporates{{$corporates->id}}">
                                                  <th scope="row">{{$corporates->id}}</th>
                                                  <td class="column_login" title="{{$corporates->email}}">{{$corporates->email}}</td>
                                                  <td class="column_name" title="{{$corporates->name}}">{{$corporates->name}}</td>
                                                  <td class="column_name" title="{{$corporates->tel}}">{{$corporates->tel}}</td>
                                                  <td class="column_name" title="{{$corporates->created_at}}">{{$corporates->created_at}}</td>
                                                </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="profileAdmin" tabindex="-1" role="dialog" aria-labelledby="profileAdminLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="profileAdminLabel">Редактировать Профиль</h4>
                        </div>
                        <div class="modal-body">
                            <form id="frmTasks" name="frmTasks" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="login" class="col-sm-3 control-label">Логин</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="login" name="login" placeholder="Логин" value="" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Имя</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Имя" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tel" class="col-sm-3 control-label">Телефон</label>
                                    <div class="col-sm-9">
                                        <input type="tel" class="form-control" id="tel" name="tel" placeholder="Номер телефона" value="" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-3 control-label">Пароль</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Пароль" value="" pattern="[A-Za-z0-9_-]{4,8}" />
                                        <span id="msg"><b style="display: none" id="msg_ask">Сложность:</b> <i id="answer"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="verified" class="col-sm-3 control-label">Подтверждение</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="verified" name="verified" placeholder="Подтверждение" value="">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save_admin" value="add">Изменить</button>
                            <input type="hidden" id="admin_id" name="admin_id" value="0">
                            <input type="hidden" id="main_admin" name="main_admin" value="0">
                        </div>
                    </div>
                </div>
        </div>

        <div class="modal fade" id="Delete_Change" tabindex="-1" role="dialog" aria-labelledby="Delete_Change" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="Delete_Change">{{Auth::user()->name}}, <span id="Delete_Change_ask">Вы уверены что хотите это сделать?</span></h4>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div style="display: flex; justify-content: space-around;">
                                    <button type="button" class="btn btn-danger" id="btn_ask" value="">Уверен</button>
                                    <button type="button" class="btn btn-primary" id="btn_cancel" value="cancel">Отменить</button>
                                    <input type="hidden" id="admin_id" name="admin_id" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        
        <div class="modal fade" id="profileUser" tabindex="-1" role="dialog" aria-labelledby="profileAdminLabel" aria-hidden="true">
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
                                <div class="form-group">
                                    <label for="verified" class="col-sm-3 control-label">Подтверждение</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="verified_user" name="verified" placeholder="Подтверждение" value="">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save_user" value="add">Изменить</button>
                            <input type="hidden" id="user_id" name="admin_id" value="0">
                            <input type="hidden" id="main_admin" name="main_admin" value="0">
                        </div>
                    </div>
                </div>
        </div>

        <div class="modal fade" id="Delete_Change_user" tabindex="-1" role="dialog" aria-labelledby="Delete_Change" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="Delete_Change">{{Auth::user()->name}}, <span id="Delete_Change_ask_user">Вы уверены что хотите это сделать?</span></h4>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div style="display: flex; justify-content: space-around;">
                                    <button type="button" class="btn btn-danger" id="btn_ask_user" value="">Уверен</button>
                                    <button type="button" class="btn btn-primary" id="btn_cancel_user" value="cancel">Отменить</button>
                                    <input type="hidden" id="user_id" name="user_id" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
</main>

<script src="{{asset('assets/js/admins_home_ajax.js')}}"></script>
<script src="{{asset('assets/js/users_home_ajax.js')}}"></script>
<script src="{{asset('assets/js/ProjectManajer.js')}}"></script>
<script src="{{asset('assets/js/SeasonManager.js')}}"></script>
<input type="hidden" id="count_users" value="{{ $count_users }}">
@endsection
