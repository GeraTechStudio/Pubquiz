<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainPage@index')->name('home');
Route::get('/Rules', 'MainPage@showRules')->name('rules');
Route::get('/Rating', 'GlobalRating@showRatingForm')->name('rating');
Route::get('/Rating/result', 'GlobalRating@showRating')->name('rating_result');
Route::get('/Contacts', 'MainPage@showContacts')->name('contacts');

Route::post('/corporateApply', 'MainPage@CorporateApply');

Route::get('/checkAuth', 'MainPage@checkAuth');





Route::get('/Calendar/Games', 'TabsController@CalendarGameShow')->name('GameCalendar');
Route::get('/Calendar/Games/get/data', 'TabsController@getGames');
Route::get('/Calendar/Games/get/closer_game', 'TabsController@getCloserGame')->middleware(['auth']);
Route::get('/Calendar/Game/{game_id?}', 'TabsController@ShowGame')->name('Game');
Route::get('/Calendar/get/Command/{id_game?}', 'TabsController@getCommandImg');
Route::get('/Calendar/get/Pub/{command_id?}', 'TabsController@getPub');
Route::get('/Calendar/get/createdCommandsUser/{id_game?}', 'TabsController@getCreatedCommandsUser');
Route::post('/Calendar/addCommand/{id_game?}', 'TabsController@addCommand');
Route::delete('/Calendar/game/cancel_command/{id_game?}', 'TabsController@cancelCommand');
Route::post('/Calendar/apply/inCommand/{id_game?}', 'TabsController@applyinCommand');


/*Роуты Авторизация*/
	Auth::routes();
	Route::get('register/confirm/{token}', 'Auth\RegisterController@confirmEmail');
/*----------------*/




Route::get('/home', 'HomeController@main')->name('home');
Route::get('/home/getUser/{user_id?}', 'HomeController@getUser');
Route::get('/home/get/commandConsist/{command_id?}', 'HomeController@getCommandConsist');
Route::post('/home/get/commandConsist/applyUser', 'HomeController@applyUser');
Route::post('/home/get/commandConsist/deleteUser', 'HomeController@deleteUser');
Route::post('/home/get/commandConsist/cancelUser', 'HomeController@cancelUser');
Route::get('/home/get/commandConsist/get_command', 'HomeController@getCommand');
Route::get('/home/get/commandConsist/checkCommand/check', 'HomeController@checkCommand');
Route::delete('/home/delete/commandConsist/ExitCommand', 'HomeController@ExitCommand');
Route::put('/home/change/{user_id?}', 'HomeController@changeUser');
Route::post('/home/upload/Avatar', 'HomeController@uploadAvatar');

Route::get('/home/checkUnique/Command_name', 'HomeController@UniqueCommand');
Route::post('/home/create/Command', 'HomeController@createCommand');
Route::post('/home/upload/CommandAvatar', 'HomeController@uploadCommandAvatar');
Route::post('/home/change/Command', 'HomeController@changeCommand');
Route::delete('/home/delete/Command', 'HomeController@deleteCommand');
Route::get('/home/myCommand/{command_id?}', 'HomeController@showMyCommand')->name('showMyCommand');
Route::get('/home/myCommand/getPub/{pub_id?}', 'HomeController@getPub');
Route::get('/home/myCommandApplyers/{game_id?}/{command_id?}', 'HomeController@myCommandApplyers');
Route::post('/home/myCommand/AcceptApply/{id_user?}', 'HomeController@myCommandApplyUser');
Route::delete('/home/myCommand/DeleteApply/{id_user?}', 'HomeController@myCommandDeleteUser');

Route::prefix('admin')->middleware(['auth', 'access:admin'])->group(function () {

    Route::get('/getToolbar/order', 'Admin\AdminHomePageController@ToolbarOrder');
    Route::get('/get/users', 'Admin\AdminHomePageController@ToolbarGetUsers');
    Route::get('/get/user', 'Admin\AdminHomePageController@ToolbarGetUser');
    Route::get('/', 'Admin\AdminHomePageController@showPage')->name('showAdminPage');
    Route::put('/{admin_id?}', 'Admin\AdminHomePageController@changeAdminStatus');
    Route::get('/{admin_id?}', 'Admin\AdminHomePageController@showAdmin');
    Route::put('/change/{admin_id?}', 'Admin\AdminHomePageController@changeAdmin');
    Route::delete('/delete/{admin_id?}', 'Admin\AdminHomePageController@deleteAdmin');

    Route::prefix('user')->group(function () {
    	Route::put('/{user_id?}', 'Admin\AdminHomePageController@changeUserStatus');
	    Route::get('/{user_id?}', 'Admin\AdminHomePageController@showUser');
	    Route::put('/change/{user_id?}', 'Admin\AdminHomePageController@changeUser');
	    Route::delete('/delete/{user_id?}', 'Admin\AdminHomePageController@deleteUser');
    });

    Route::prefix('game')->group(function (){
        Route::get('/calendar', 'Admin\AdminGameController@showCalendar')->name('ShowGame');
        // Route::get('/get/Seasons','Admin\AdminGameController@getSeasons');
        // Route::get('/get/Projects','Admin\AdminGameController@getProjects');
        Route::get('/get/data','Admin\AdminGameController@getCalendarData');
        Route::post('/post/GameBuffer','Admin\AdminGameController@postGameBuffer');
        Route::get('/get/Resources','Admin\AdminGameController@getResources');
        Route::get('/closer_game','Admin\AdminGameController@getCloserGame');
    });

    Route::prefix('game/creation')->group(function (){
        Route::get('/get/{game_id?}', 'Admin\Game\IndexController@getGameConfirmed');
        Route::put('/changeConfirmedGame/{game_id?}', 'Admin\Game\IndexController@changeConfirmed');


        Route::get('/{game_id?}', 'Admin\AdminCreationGameController@showCreationGame')->name('CreationGame');
        Route::get('/get_buff/{game_id?}', 'Admin\AdminCreationGameController@getBufferGame');
        Route::get('/get_command/{game_id?}/{pub_id?}', 'Admin\AdminCreationGameController@getCommands');
        Route::post('/add_command{game_id?}', 'Admin\AdminCreationGameController@addCommands');
        Route::post('/upload', 'Admin\AdminCreationGameController@addImg');
        Route::post('/recreate/project', 'Admin\AdminCreationGameController@recreateProject');
        Route::post('/recreate/season', 'Admin\AdminCreationGameController@recreateSeason');

        Route::put('/update/game_name/{game_id?}', 'Admin\AdminCreationGameController@updateGameName');
        Route::put('/update/game_date/{game_id?}', 'Admin\AdminCreationGameController@updateGameDate');
        Route::put('/update/game_time/{game_id?}', 'Admin\AdminCreationGameController@updateGameTime');
        Route::put('/update/game_project/{game_id?}', 'Admin\AdminCreationGameController@updateGameProject');
        Route::put('/update/game_season/{game_id?}', 'Admin\AdminCreationGameController@updateGameSeason');
        Route::put('/update/game_desc/{game_id?}', 'Admin\AdminCreationGameController@updateGameDesc');

        Route::put('/update/game_rounds/{game_id?}', 'Admin\AdminCreationGameController@updateGameRounds');
        Route::put('/update/game_order/{game_id?}', 'Admin\AdminCreationGameController@updateGameOrder');

        Route::put('/update/game_pubs/{game_id?}', 'Admin\AdminCreationGameController@updateGamePubs');
        Route::put('/update/game_pub/tables/{game_id?}', 'Admin\AdminCreationGameController@updateTables');
        Route::put('/change/reserve_tables/{game_id?}', 'Admin\AdminCreationGameController@changeReserveTables');
        Route::put('/update/game_stage/{game_id?}', 'Admin\AdminCreationGameController@updateStage');
        Route::delete('/delete/game_pub/{game_id?}', 'Admin\AdminCreationGameController@deleteGamePub');
        Route::delete('/deleteGame/{game_id?}', 'Admin\AdminCreationGameController@deleteGame');
    });


    Route::prefix('Location')->group(function () {
        Route::get('/Manager', 'Admin\ManagedPub@showPage')->name('ShowManagerPub');
        Route::delete('/Manager/delete_img', 'Admin\ManagedPub@deleteIMG');
        Route::post('/Manager/upload', 'Admin\ManagedPub@addImg');
        Route::delete('/Manager/delete_img', 'Admin\ManagedPub@deleteImg');
        Route::post('/Manager/crate_pub', 'Admin\ManagedPub@addLocation');
        Route::get('/Manager/get_pub/{pub_id?}', 'Admin\ManagedPub@getPubID');
        Route::get('/Manager/type_of_pub', 'Admin\ManagedPub@getTypeofPub');
    });

    Route::prefix('Project')->group(function () {
        Route::post('/add', 'Admin\AdminHomePageController@addProject');
        Route::get('/{project_id?}', 'Admin\AdminHomePageController@getProjectID');
        Route::delete('/delete/{project_id?}', 'Admin\AdminHomePageController@deleteProjectID');
        Route::put('/change/{project_id?}', 'Admin\AdminHomePageController@changeProjectID');
    });

    Route::prefix('Season')->group(function () {
        Route::post('/add', 'Admin\AdminHomePageController@addSeason');
        Route::get('/{season_id?}', 'Admin\AdminHomePageController@getSeasonID');
        Route::delete('/delete/{season_id?}', 'Admin\AdminHomePageController@deleteSeasonID');
        Route::put('/change/{season_id?}', 'Admin\AdminHomePageController@changeSeasonID');
    });

    Route::prefix('/Location/Type')->group(function () {
        Route::post('/add', 'Admin\ManagedPub@addType');
        Route::get('/{type_id?}', 'Admin\ManagedPub@getTypeID');
        Route::delete('/delete/{type_id?}', 'Admin\ManagedPub@deleteTypeID');
        Route::put('/change/{type_id?}', 'Admin\ManagedPub@changeTypeID');
    });

    Route::prefix('/Location/Manipulate')->group(function () {
        Route::get('/type_of_pub', 'Admin\ManagedPub@getTypeofPub');
        Route::get('/{pub_id?}', 'Admin\ManagedPub@getSpecialPubID');
        Route::put('/change/{pub_id?}', 'Admin\ManagedPub@changePubID');
        Route::delete('/delete_pub/{pub_id?}', 'Admin\ManagedPub@deletePubID');
        Route::get('/get/all_elements', 'Admin\ManagedPub@getallEllements');
        Route::get('/get/elements', 'Admin\ManagedPub@getEllements');
        Route::get('/get/getAllPubs', 'Admin\ManagedPub@getAllPubs');
    });    
});

Route::get('/admin/game/creation/get/pubCheck/{game_id?}', 'Admin\AdminCreationGameController@pubCheck');
Route::get('/admin/game/creation/get/location/{location_id?}', 'Admin\AdminCreationGameController@findLocation');
Route::get('/admin/game/creation/get/pubs/{game_id?}', 'Admin\AdminCreationGameController@showPubs');
Route::post('/admin/game/creation/load_rating/{game_id?}', 'Admin\AdminCreationGameController@loadRating');
Route::put('/admin/game/creation/update_command_rating/{game_id?}', 'Admin\AdminCreationGameController@UpdateCommand');
Route::get('/admin/game/get_command', 'Admin\AdminCreationGameController@getCommand');
Route::get('/admin/game/get_absent_command/{game_id?}/{pub_id?}', 'Admin\AdminCreationGameController@GetAbsent');

Route::get('/admin/game/files', 'Admin\AdminHomePageController@indexfile');
Route::post('/admin/game/getfiles', 'Admin\AdminHomePageController@getFile')->name('postFile');


// Route::get('/loadDB', 'Admin\GlobalRating@load');
