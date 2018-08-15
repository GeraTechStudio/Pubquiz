<?php

namespace App\Http\Controllers\Admin;

use App\Location;
use App\Season;
use App\Projects;
use App\GameBuffer;
use App\ReserveTable;
use App\Commands;
use App\GameCommandContents;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Config;
use App\Rating;
use App\TemporaryResult;
use Excel;
use App\User;

class AdminCreationGameController extends Controller
{
    public function __construct(RegionRepository $regionModel){ 
        $this->region = $regionModel;
        $this->global = [];
        $this->absent_command = [];
        $this->absent_index = [];
        $this->model = "";   
    }

    public function showCreationGame($id_game){
    	$regions = $this->getRegion();
    	$Rgns = view(config('EnvSettings.THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(config('EnvSettings.THEME').'.layouts.navigation')->render();
        $game = GameBuffer::find($id_game);
        if($game->confirmed == 2){
            $table = ReserveTable::where('id_game', '=', $id_game)->get();
            $count = 0;

            if(count($table)!=0){
                if($table[0]->reserve != "None"){
                    $reserve_tables = explode(',', $table[0]->reserve );
                    foreach ($reserve_tables as $reserve_table) {
                        $reserve_table = explode('=>', $reserve_table );
                        $pub_reserve = explode('[', $reserve_table[0] );
                        $pub_reserve = $pub_reserve[1];

                        $location = Location::find($pub_reserve);

                        $full_table = explode('->', $reserve_table[1] );
                        $users = $full_table[1];
                        $users = explode(']', $users);$users = $users[0];$users = explode('(', $users);$users = $users[1];$users = explode(')', $users); $users = $users[0];

                        $addCommand = "";
                        $commandCount = 0;

                        if($users != "None"){
                            $commands = explode(' | ', $users);
                            foreach ($commands as $command) {
                                $command = explode('=', $command);                    
                                $command = $command[1]; 
                                $present = Commands::find($command);
                                if(!empty($present)){
                                    if($commandCount == 0){
                                        $addCommand .= "". $present->command_name . "=" . $present->id;
                                        $commandCount++;
                                    }else{
                                       $addCommand .= " | ". $present->command_name . "=" . $present->id;
                                       $commandCount++; 
                                    }
                                }
                            }
                        }

                        if($commandCount == 0){
                            $addCommand = "None" ;
                        }

                        
                        $full_table = $full_table[0];
                        if(!empty($location)){
                            $count++;
                            if($count == 1){
                                if($full_table == 0){
                                    $table[0]->reserve = "[".$location->id. "=>". $full_table ."->(None)]";
                                }
                                else{
                                    $table[0]->reserve = "[".$location->id. "=>". $full_table ."->(". $addCommand . ")]";
                                }
                                
                            }
                            else{
                                if($full_table == 0){
                                    $table[0]->reserve = "".$table[0]->reserve.",[".$location->id. "=>". $full_table ."->(None)]";
                                }
                                else{
                                    $table[0]->reserve = "".$table[0]->reserve.",[".$location->id. "=>". $full_table ."->(". $addCommand . ")]";
                                }
                            }
                        }
                        if($count == 0){
                            $table[0]->reserve = "None";
                            
                        }
                    }
                    $table[0]->save();

                    $reserve_tables = explode(',', $table[0]->reserve);
                }
            }else{
                $reserve_tables = "None";
            }

            $pub_array = [];
            $result_array = [];
            if($game->pubs !="None"){
                $pubs_array = $game->pubs;
                $pubs = explode(',', $pubs_array );
                $count = 0;
                foreach ($pubs as $pub) {
                    $pub_peace = explode('=', $pub );
                    $pub_id = explode('[', $pub_peace[0] );
                    $pub_table = explode(']', $pub_peace[1] );
                    $location = Location::find($pub_id[1]);
                    if(!empty($location)){

                        /******/
                        $temp_res = TemporaryResult::where('game_id', '=', $id_game)->where('location_id', '=', $pub_id[1])->first();
                        if(!empty($temp_res)){
                            $count_absent = 0;
                            $first = true;
                            $game_data_buf = "None";
                            $buffer_result = $temp_res->game_data;
                            if($buffer_result != "Error"){
                                $buffer_results = explode(',', $buffer_result);
                                foreach ($buffer_results as $buffer_result) {
                                    $buf_item = $buffer_result;
                                    $is_author = explode('(',$buffer_result);$is_author = explode(')',$is_author[1]);
                                    if($is_author[0] == "Author"){
                                       $is_author = true; 
                                    }else{
                                       $is_author = false; 
                                    }
                                    $buffer_result = explode('_', $buffer_result);$command_id = explode('[', $buffer_result[0]);
                                    $command_id = $command_id[1];
                                    if($command_id == "?"){
                                        if($first == true){
                                            $game_data_buf = $buf_item;
                                            $first = false;
                                        }else{
                                            $game_data_buf .= "," . $buf_item;
                                        }
                                            if($is_author != true){
                                                $count_absent++;
                                            }
                                    }else{
                                        $present = Commands::find($command_id);
                                        if(!empty($present)) {
                                            $buffer_result = explode('(', $buffer_result[1]);
                                               if($first == true){
                                                    $game_data_buf = "[". $present->id . "_" . $present->command_name . "(" . $buffer_result[1];
                                                    $first = false;
                                                }else{
                                                    $game_data_buf .= ",[". $present->id . "_" . $present->command_name . "(" . $buffer_result[1];
                                                } 
                                        }else{
                                            if($first == true){
                                                    $game_data_buf = "[?_" . $buffer_result[1];
                                                    $first = false;
                                            }else{
                                                $game_data_buf .= ",[?_" . $buffer_result[1];
                                            }
                                            if($is_author != true){
                                                $count_absent++;
                                            }
                                        }
                                    } 
                                    $temp_res->game_data = $game_data_buf;
                                    $temp_res->save();    
                                }
                                $temp_res = array_add($temp_res, 'count_absent', $count_absent);
                                $result_array = array_add($result_array, $pub_id[1], $temp_res);
                            }else{
                               $result_array = array_add($result_array, $pub_id[1], "Empty"); 
                            }
                        }else{
                            $result_array = array_add($result_array, $pub_id[1], "Empty");
                        }
                        

                        /********/
                        $pub_array = array_add($pub_array, $reserve_tables[$count], $location);
                        $count++;
                        if($count == 1){
                            $game->pubs = "[".$location->id. "=". $pub_table[0] ."]";
                        }
                        else{
                            $game->pubs = "" . $game->pubs . ",[".$location->id. "=". $pub_table[0] ."]";
                        }
                    }
                    if($count == 0){
                        $game->pubs = "None";
                    }
                }
                $game->save();
            }

            /***/
            if(!empty($pub_array)){
                $final_rating = Rating::where('game_id', $id_game)->first();
                if(!empty($final_rating)){
                    $final_rating->game_name = $game->game_name;
                    $final_rating->game_id = $game->id;
                    $final_rating->id_project = $game->id_project;
                    $final_rating->id_season = $game->id_season;
                    $final_rating->round_size = $game->game_rounds;
                    $final_rating->game_order = $game->game_order ;
                    $final_rating->region = config('EnvSettings.REGION');     
                }else{
                    $final_rating = new Rating;
                    $final_rating->game_name = $game->game_name;
                    $final_rating->game_id = $game->id;
                    $final_rating->id_project = $game->id_project;
                    $final_rating->id_season = $game->id_season;
                    $final_rating->round_size = $game->game_rounds;
                    $final_rating->game_order = $game->game_order ;
                    $final_rating->game_data = "None";
                    $final_rating->region = config('EnvSettings.REGION');
                }

                $final_rating_array = [];
                $final_rating_author = "";
                foreach ($pub_array as $key => $value) {
                   $temp_res = TemporaryResult::where('game_id', $id_game)->where('location_id', $value->id)->first();
                   if(!empty($temp_res)){
                       $buffer_temp_result = $temp_res->game_data;
                       if($buffer_temp_result != "Error"){
                            $buffer_temp_results = explode(',', $buffer_temp_result);
                                foreach ($buffer_temp_results as $buffer_temp_result) {
                                    $atr = explode('(', $buffer_temp_result);$atr = explode(')', $atr[1]);
                                    if($atr[0] !="Author"){
                                        $rating_result = explode('=>', $buffer_temp_result);
                                        $rating_result = explode(']', $rating_result[1]);
                                        $buffer_temp_result .= "->" . $value->color;
                                        
                                        foreach ($final_rating_array as $key_rating => $value_rating) {
                                            if($key_rating == $rating_result[0]){
                                                $buffer_temp_result = $value_rating . "," . $buffer_temp_result;
                                                unset($final_rating_array[$key_rating]);
                                            }
                                        }
                                        $final_rating_array = array_add($final_rating_array, $rating_result[0], $buffer_temp_result);
                                    }else{
                                       $final_rating_author = $buffer_temp_result . "->Author"; 
                                    }
                                }        
                       }
                        
                   }else{
                        continue;
                   }
                }  
            
            if(!empty($final_rating_author)){
                $rating_result = explode('=>', $final_rating_author);
                $rating_result = explode(']', $rating_result[1]);

                foreach ($final_rating_array as $key_rating => $value_rating) {
                    if($key_rating == $rating_result[0]){
                        $buffer_temp_result = $final_rating_author . "," . $value_rating;
                        unset($final_rating_array[$key_rating]);
                    }
                }
                $final_rating_array = array_add($final_rating_array, $rating_result[0], $buffer_temp_result);
            }

            
                
            if(!empty($final_rating_array)){
                krsort($final_rating_array);
                $load_data = false;
                foreach ($final_rating_array as $value_rating) {
                    if($load_data == false){
                       $final_rating->game_data = $value_rating;
                       $load_data = true;
                    }else{
                       $final_rating->game_data .= "," . $value_rating;
                    } 
                }
            }

            $final_rating->save();
        }else{
           $final_rating = []; 
        }
            return view(config('EnvSettings.THEME').'.Admin.GameCreation', ['region'=>$Rgns, 'game'=>$game, 'navigation'=>$NVG, 'locations'=>$pub_array, 'rating'=>$result_array, 'final_rating'=>$final_rating]);
        }
    	return view(config('EnvSettings.THEME').'.Admin.GameCreation', ['region'=>$Rgns, 'game'=>$game, 'navigation'=>$NVG]);
    }


    public function getCommands($id_game, $pub_id){
        $reserve = ReserveTable::find($id_game);
        $reserve = $reserve->reserve;
        $pub_content = "None";
        if($reserve != "None"){
            $reserves = explode(',', $reserve);
            foreach ($reserves as $value) {
                $pub = explode('=>', $value);
                $pub = explode('[', $pub[0]);
                if($pub[1] == $pub_id){
                   $pub_content = $value;
                   break; 
                } 
            }
            $commands_array = explode('(',$value);
            $commands_array = explode(')',$commands_array[1]);
            if($commands_array[0] != "None"){
               $commands_array = explode(' | ',$commands_array[0]); 
               $command_content_array = [];
               foreach ($commands_array as $command) {
                    $command = explode('=', $command);
                    $command_id = $command[1];
                    $command = Commands::find($command_id);
                    if(!empty($command)){
                        $players = GameCommandContents::where('command_id', '=', $command_id)->where('game_id','=',$id_game)->first();
                        
                        if(!empty($players)){
                            if($players->players == "None"){
                                $command = array_add($command, 'status', "Delete");
                                $command = array_add($command, 'places', 0);
                            }else{
                                $players_place = explode(' | ', $players->players); $players_place = count($players_place);
                                if($players_place == 6){
                                    $command = array_add($command, 'status', "Delete");
                                    $command = array_add($command, 'places', 6);
                                }else{
                                    $command = array_add($command, 'status', "Delete");
                                    $command = array_add($command, 'places', $players_place);
                                }
                            }
                        }    
                        array_push($command_content_array,$command);        
                    }
                    
               }
               return Response::json($command_content_array); 
            }else{
                return Response::json("Absent");  
            }
        }else{
           return Response::json("NotFound");  
        }
    }

    public function addCommands($id_game, Request $request){
        $command = Commands::where('command_name', $request->command_name)->first();
        if(empty($command)){
           return Response::json('Absent'); 
        }else{
            $table = ReserveTable::where('id_game', '=', $id_game)->first();
            $count = 0;
            $attribute = 0;
            if(!empty($table)){
                $pubs = explode(',', $table->reserve);
                foreach ($pubs as $pub){
                    $pub_part = explode('=>', $pub); $pub_id = explode('[', $pub_part[0]); $pub_id = $pub_id[1];
                    if($pub_id == $request->Pub_id){
                        $pub_commands = explode('->', $pub_part[1]); $pub_free_places = $pub_commands[0]; $pub_commands = explode(']', $pub_commands[1]); $pub_commands = explode('(', $pub_commands[0]); $pub_commands = explode(')', $pub_commands[1]); $pub_commands = $pub_commands[0];

                        if($count == 0){

                            if($pub_commands == "None"){
                                $table->reserve = "[" . $pub_id . "=>" . $pub_free_places . "->(" . $command->command_name . "=". $command->id . ")]";
                                $attribute = $command->command_name . "=". $command->id;
                            }else{
                                $table->reserve = "[" . $pub_id . "=>" . $pub_free_places . "->(" . $pub_commands . " | " . $command->command_name . "=". $command->id . ")]";
                                $attribute = $pub_commands . " | " . $command->command_name . "=". $command->id;
                            }
                            $count++;

                        }else{
                            if($pub_commands == "None"){
                                $table->reserve = "".  $table->reserve. ",[" . $pub_id . "=>" . $pub_free_places . "->(" . $command->command_name . "=". $command->id . ")]";
                                $attribute = $command->command_name . "=". $command->id;
                            }else{
                                $table->reserve = "".  $table->reserve. ",[" . $pub_id . "=>" . $pub_free_places . "->(" . $pub_commands . " | " . $command->command_name . "=". $command->id . ")]";
                                $attribute = $pub_commands . " | " . $command->command_name . "=". $command->id;
                            }
                        }
                               
                    }else{
                        if($count == 0){
                            $table->reserve = "" . $pub;
                            $count++; 
                        }else{
                            $table->reserve = "". $table->reserve . "," . $pub;
                        }
                    } 
                    
                }
                $table->save();
            }
            $user = User::find($command->id_user);
            $game_contents = new GameCommandContents;
            $game_contents->game_id = $id_game;
            $game_contents->command_id = $command->id;
            $game_contents->players = "". $user->login . "=" . $user->id;
            $game_contents->buffer_players = "None";
            $game_contents->region  = env("REGION");
            $game_contents->save();
        }
        return Response::json($command);  
    }




    public function getBufferGame($id_game){
    	$game_buffer = GameBuffer::find($id_game);
    	$project = Projects::find($game_buffer->id_project);
    	if(empty($project)){
    		$project = "None";
    	}
    	$season = Season::find($game_buffer->id_season);
    	if(empty($season)){
    		$season = "None";
    	}
    	$projects = Projects::where('Project_name', '!=', $game_buffer->project_name)->where('region', '=', config('EnvSettings.REGION'))->get();
    	$seasons = Season::where('Season_name', '!=', $game_buffer->season_name )->where('region', '=', config('EnvSettings.REGION'))->get();
    	$locations = Location::where('region', '=', config('EnvSettings.REGION'))->get();
    	$Answer = ['game'=>$game_buffer, 'project'=>$project, 'season'=>$season, 'projects'=>$projects, 'seasons'=>$seasons, 'locations'=>$locations];
    	return Response::json($Answer);
    }


    public function addImg(Request $request){
    	$game_buffer = GameBuffer::find($request->id_game);
    	if($request->hasFile('imgGame')) {
    		$file = $request->file('imgGame');
    		if($game_buffer->game_img_name == "None"){
    			$file_name = "" . $request->id_game . $file->getClientOriginalName();
    			$img_path = url('storage') . "/Game_img/";
    			$file->move(storage_path('app/public/'.Config::get('settings.Game_img_path')),$file_name);
    			$game_buffer->game_img_name = $file_name;
    			$game_buffer->game_img_path  = "". $img_path . $file_name;
    			$game_buffer->save();
    			$Answer = ['img_path' => $game_buffer->game_img_path];
    		}
    		else{
    			unlink(storage_path('app/public/' . Config::get('settings.Game_img_path') . "/" . $game_buffer->game_img_name));
    			$file_name = "" . $request->id_game . $file->getClientOriginalName();
    			$img_path = url('storage') . "/Game_img/";
    			$file->move(storage_path('app/public/'.Config::get('settings.Game_img_path')),$file_name);
    			$game_buffer->game_img_name = $file_name;
    			$game_buffer->game_img_path  = "". $img_path . $file_name;
    			$game_buffer->save();
    			$Answer = ['img_path' => $game_buffer->game_img_path];
    		}

    	}
    	else{
    		if($game_buffer->game_img_name == "None"){
    			
    			$Answer = ['img_path' => "None"];
    		}
    		else{
    			$Answer = ['img_path' => $game_buffer->game_img_path];
    		}
    	}	
    	return Response::json($Answer);
    }

    public function recreateProject(Request $request){
    	$newProject = new Projects;
    	$newProject->id = $request->id_project;
    	$newProject->Project_name = $request->project_name;
    	$newProject->Project_color = $request->project_color;
    	$newProject->region = config('EnvSettings.REGION');
    	$newProject->save();
    	return Response::json($newProject);
    }

    public function recreateSeason(Request $request){
    	$newSeason = new Season;
    	$newSeason->id = $request->id_season;
    	$newSeason->Season_name = $request->season_name;
    	$newSeason->region = config('EnvSettings.REGION');
    	$newSeason->save();
    	return Response::json($newSeason);
    }
    



    /*Fast upploading data*/

    public function updateGameName(Request $request, $game_id){
    	$game_buffer = GameBuffer::find($game_id);
    	$game_buffer->game_name = $request->Game_name;
    	$game_buffer->save();
    	return Response::json("Okey");
    }

    public function updateGameDate(Request $request, $game_id){
    	$game_buffer = GameBuffer::find($game_id);
    	$game_buffer->game_date = $request->Game_date;
    	$game_buffer->save();
    	return Response::json("Okey");
    }

    public function updateGameTime(Request $request, $game_id){
    	$game_buffer = GameBuffer::find($game_id);
    	$game_buffer->game_time = $request->Game_time;
    	$game_buffer->save();
    	return Response::json("Okey");
    }

    public function updateGameProject(Request $request, $game_id){
    	$game_buffer = GameBuffer::find($game_id);
    	$project = Projects::find($request->Game_Project);
    	$game_buffer->id_project = $project->id;
    	$game_buffer->project_name  = $project->Project_name;
    	$game_buffer->project_color  = $project->Project_color;
    	$game_buffer->save();
    	return Response::json("Okey");
    }

    public function updateGameSeason(Request $request, $game_id){
    	$game_buffer = GameBuffer::find($game_id);
    	$season = Season::find($request->Game_Season);
    	$game_buffer->id_season = $season->id;
    	$game_buffer->season_name  = $season->Season_name;
    	$game_buffer->save();
    	return Response::json("Okey");
    }

    public function updateGameRounds(Request $request, $game_id){
    	$game_buffer = GameBuffer::find($game_id);
    	$game_buffer->game_rounds = $request->Game_rounds;
    	$game_buffer->save();
    	return Response::json("Okey");
    }

    public function updateGameOrder(Request $request, $game_id){
        $game_buffer = GameBuffer::find($game_id);
        $game_buffer->game_order = $request->Game_order;
        $game_buffer->save();
        return Response::json("Okey");
    }

    public function updateGameDesc(Request $request, $game_id){
        $game_buffer = GameBuffer::find($game_id);
        $game_buffer->game_desc = $request->Game_Desc;
        $game_buffer->save();
        return Response::json("Okey");
    }

    public function updateGamePubs(Request $request, $game_id){
    	$game_buffer = GameBuffer::find($game_id);
        $table = ReserveTable::where('id_game', '=', $game_id)->get();
    	if($game_buffer->pubs =="None"){
    		$game_buffer->pubs = "[".$request->Game_Pubs. "=1]"; 
            $table[0]->reserve = "[".$request->Game_Pubs."=>0->(None)]";
    	}
    	else{
    		$game_buffer->pubs = "" . $game_buffer->pubs . ",[".$request->Game_Pubs. "=0]";
            $table[0]->reserve = "" . $table[0]->reserve . ",[".$request->Game_Pubs."=>0->(None)]";
    	}
    	$game_buffer->save();
        $table[0]->save();
    	return Response::json("Okey");
    }


    public function pubCheck($game_id){ /*Выводит только те пабы, что еще не выбраны*/
    	$game_buffer = GameBuffer::find($game_id);
    	$pubs_array = $game_buffer->pubs;
    	$pubs = explode(',', $pubs_array );
    	$locations = Location::all();
    	foreach ($pubs as $pub) {
    		$pub_peace = explode('=', $pub );
    		$pub_id = explode('[', $pub_peace[0] );
    		$locations = $locations->where('id','!=',$pub_id[1]);
    	}
    	$array = array();
    	foreach ($locations as $key => $location) {
    		array_push($array,$key);
    	}
    	return Response::json(['locations'=>$locations, 'array'=>$array]);
    }

    public function findLocation($local_id){
    	$location = Location::find($local_id);
    	return Response::json($location);
    }

    public function showPubs($game_id){
    	$game_buffer = GameBuffer::find($game_id);
        $table = ReserveTable::where('id_game', '=', $game_id)->get();
    	$pubs_array = $game_buffer->pubs;
    	$pubs = explode(',', $pubs_array);
    	$pub_array = [];
    	$pub_array_index = [];
    	$pub_tables = [];
    	$count = 0;
    	foreach ($pubs as $pub) {
    		$pub_peace = explode('=', $pub );
    		$pub_id = explode('[', $pub_peace[0] );
    		$pub_table = explode(']', $pub_peace[1] );
    		$location = Location::find($pub_id[1]);
    		if(!empty($location)){
	    		$pub_array = array_add($pub_array, $location->id, $location);
	    		$pub_tables = array_add($pub_tables, $location->id, $pub_table[0]);
	    		array_push($pub_array_index, $location->id);
	    		$count++;
	    		if($count == 1){
	    			$game_buffer->pubs = "[".$location->id. "=". $pub_table[0] ."]";
	    		}
	    		else{
	    			$game_buffer->pubs = "" . $game_buffer->pubs . ",[".$location->id. "=". $pub_table[0] ."]";
	    		}
	    	}
	    	if($count == 0){
	    		$game_buffer->pubs = "None";
	    	}
    	}
    	$game_buffer->save();

        $count = 0;
        $reserve_tables = explode(',', $table[0]->reserve );
        foreach ($reserve_tables as $reserve_table) {
            $reserve_table = explode('=>', $reserve_table );
            $pub_reserve = explode('[', $reserve_table[0] );
            $pub_reserve = $pub_reserve[1];

            $location = Location::find($pub_reserve);

            $full_table = explode('->', $reserve_table[1] );
            $users = $full_table[1];
            $users = explode(']', $users);$users = $users[0];$users = explode('(', $users);$users = $users[1];$users = explode(')', $users); $users = $users[0];

            $addCommand = "";
            $commandCount = 0;

            if($users != "None"){
                $commands = explode(' | ', $users);
                foreach ($commands as $command) {
                    $command = explode('=', $command);                    
                    $command = $command[1]; 
                    $present = Commands::find($command);
                    if(!empty($present)){
                        if($commandCount == 0){
                            $addCommand .= "". $present->command_name . "=" . $present->id;
                            $commandCount++;
                        }else{
                           $addCommand .= " | ". $present->command_name . "=" . $present->id;
                           $commandCount++; 
                        }
                    }
                }
            }

            if($commandCount == 0){
                $addCommand = "None" ;
            }

            
            $full_table = $full_table[0];
            if(!empty($location)){
                $count++;
                if($count == 1){
                    if($full_table == 0){
                        $table[0]->reserve = "[".$location->id. "=>". $full_table ."->(None)]";
                    }
                    else{
                        $table[0]->reserve = "[".$location->id. "=>". $full_table ."->(". $addCommand . ")]";
                    }
                    
                }
                else{
                    if($full_table == 0){
                        $table[0]->reserve = "".$table[0]->reserve.",[".$location->id. "=>". $full_table ."->(None)]";
                    }
                    else{
                        $table[0]->reserve = "".$table[0]->reserve.",[".$location->id. "=>". $full_table ."->(". $addCommand . ")]";
                    }
                }
            }
            if($count == 0){
                $table[0]->reserve = "None";
                
            }
        }

        $table[0]->save();

    return Response::json(['locations'=>$pub_array, 'index'=>$pub_array_index, 'tables'=>$pub_tables, 'reserve'=> $table[0]]);
    }

    public function changeReserveTables($game_id, Request $request){

        $table = ReserveTable::where('id_game', '=', $game_id)->get();
        $reserve_tables = explode(',', $table[0]->reserve);
        $count = 0;
        foreach ($reserve_tables as $reserve_table) {
            $reserve_table = explode('=>', $reserve_table);
            $pub_id = explode('[', $reserve_table[0]);
            $pub_id = $pub_id[1];
            if($pub_id == $request->pub_id){
                $content_game_pub = explode(']', $reserve_table[1]);
                $content_game_pub = explode('->', $content_game_pub[0]);
                $game_pub_tables = $content_game_pub[0];
                $game_pub_commands = $content_game_pub[1];

                if($request->tables_amount <= 0){
                    return Response::json(["Not Allow", $game_pub_tables]);
                }

                if($game_pub_commands == "(None)" || $game_pub_tables == 0){
                    if($count == 0){
                       $table[0]->reserve = "[".$pub_id."=>".$request->tables_amount."->(None)]";
                       $count++;
                    }
                    else{
                        $table[0]->reserve = $table[0]->reserve . ",[".$pub_id."=>".$request->tables_amount."->(None)]";
                    }

                }else{
                    $players = explode('(', $game_pub_commands);$players = explode(')', $players[1]);$players = explode(' | ', $players[0]);
                    $vacant = count($players);

                    if($vacant > $request->tables_amount){
                        return Response::json(["Not Allow", $game_pub_tables]);
                    }
                    
                    
                    if($count == 0){
                       $table[0]->reserve = "[".$pub_id."=>".$request->tables_amount."->". $game_pub_commands ."]";
                       $count++;
                    }
                    else{
                        $table[0]->reserve = $table[0]->reserve . ",[".$pub_id."=>".$request->tables_amount."->". $game_pub_commands ."]";
                    }
                }

            }else{
               if($count == 0){
                    $table[0]->reserve = "" . $reserve_table[0] ."=>". $reserve_table[1];
                    $count++;
                }
                else{
                    $table[0]->reserve = $table[0]->reserve . "," . $reserve_table[0] ."=>". $reserve_table[1];;
                }    

                    
            } 
        }

        $table[0]->save();
        return Response::json("Allow");    
    }

    public function updateTables($game_id, Request $request){ /*Вносит количество Столов*/
        $game_buffer = GameBuffer::find($game_id);
        $pubs_array = $game_buffer->pubs;
        $pubs = explode(',', $pubs_array );
        $count = 0;
        foreach ($pubs as $pub) {
            $pub_peace = explode('=', $pub );
            $pub_id = explode('[', $pub_peace[0] );
            $pub_table = explode(']', $pub_peace[1] );
            $location = Location::find($pub_id[1]);
            if($pub_id[1] == $request->pub_id){
                if(!empty($location)){
                    $count++;
                    if($count == 1){
                        $game_buffer->pubs = "[".$location->id. "=". $request->tables_amount ."]";
                    }
                    else{
                        $game_buffer->pubs = "" . $game_buffer->pubs . ",[".$location->id. "=". $request->tables_amount ."]";
                    }
                }
            }else{
              if(!empty($location)){
                    $count++;
                    if($count == 1){
                        $game_buffer->pubs = "[".$location->id. "=". $pub_table[0] ."]";
                    }
                    else{
                        $game_buffer->pubs = "" . $game_buffer->pubs . ",[".$location->id. "=". $pub_table[0] ."]";
                    }
                }  
            }
                
            $game_buffer->save();
        }
        return Response::json('Okey');
            
    }

    public function updateStage($game_id, Request $request){
        $game_buffer = GameBuffer::find($game_id);
        $game_buffer->confirmed = $request->game_stage;
        $game_buffer->save();
        return Response::json('Okey');
    }

    public function deleteGamePub($game_id, Request $request){
    	$game_buffer = GameBuffer::find($game_id);
    	$pubs_array = $game_buffer->pubs;
    	$pubs = explode(',', $pubs_array );
    	$count = 0;
    	$answer = ["pub"=>"None"];
    	foreach ($pubs as $pub) {
    		$pub_peace = explode('=', $pub );
    		$pub_id = explode('[', $pub_peace[0] );
    		$pub_table = explode(']', $pub_peace[1] );
    		if($request->pub_id != $pub_id[1]){
    			$location = Location::find($pub_id[1]);
	    		if(!empty($location)){
		    		$count++;
		    		if($count == 1){
		    			$game_buffer->pubs = "[".$location->id. "=". $pub_table[0] ."]";
		    		}
		    		else{
		    			$game_buffer->pubs = "" . $game_buffer->pubs . ",[".$location->id. "=". $pub_table[0] ."]";
		    		}
		    	}
    		}
    		else{
    			$location = Location::find($request->pub_id);
    			if(!empty($location)){
    				$answer = ["pub"=>$location];
    			}
    			else{
    				$answer = ["pub"=>"None"];
    			}
    		}
    	}
		if($count == 0){
		    $game_buffer->pubs = "None";
		}
    	$game_buffer->save();


        $table = ReserveTable::where('id_game', '=', $game_id)->get();

        $count = 0;
        $reserve_tables = explode(',', $table[0]->reserve );
        foreach ($reserve_tables as $reserve_table) {
            $buffer_table = $reserve_table;
            $reserve_table = explode('=>', $reserve_table);
            $reserve_table = explode('[', $reserve_table[0]);
            $reserve_table = $reserve_table[1];
            if($request->pub_id != $reserve_table){
                $location = Location::find($reserve_table);
                if(!empty($location)){
                    $count++;
                    if($count == 1){
                        $table[0]->reserve = $buffer_table;
                    }
                    else{
                        $table[0]->reserve = "" . $table[0]->reserve . ",".$buffer_table;
                    }
                }
            }
        }

        if($count == 0){
            $table[0]->reserve = "None";
        }

        $table[0]->save(); /*Удаляет паб и  количество Столов из игры*/
        
    return Response::json($answer);	
    }

    public function deleteGame($game_id){
        $game = GameBuffer::destroy($game_id);
        return Response::json(url('/admin/game/calendar'));
    }

    public function loadRating(Request $request, $game_id){
        $game_buffer = GameBuffer::find($game_id);
        if($request->hasFile('rating')) {
            $path = "";
            $temp_res = TemporaryResult::where('game_id', '=', $game_buffer->id)->where('location_id','=',$request->pub_id)->first();
            if(!empty($temp_res)){
                $temp_res->game_id = $game_buffer->id;
                $temp_res->location_id = $request->pub_id;
                $temp_res->round_size = $game_buffer->game_rounds;
                $temp_res->order = $game_buffer->game_order;
                // $temp_res->game_data = "Error";
                $this->model = $temp_res->round_size;   
            }else{
                $temp_res = new TemporaryResult;
                $temp_res->game_id = $game_buffer->id;
                $temp_res->location_id = $request->pub_id;
                $temp_res->round_size = $game_buffer->game_rounds;
                $temp_res->order = $game_buffer->game_order;
                $temp_res->game_data = "Error";
                $this->model = $temp_res->round_size;  
            }
            
            $this->global = [];

            $path = $request->file('rating')->getRealPath();
            $data = Excel::load($path, function($reader) {
                $results = $reader->toArray();
                foreach ($results as $key => $result) {
                    $round_results = 0;
                    if(!empty($result['komandy'])){
                        $command = Commands::where('region', '=', config('EnvSettings.REGION'))->where('command_name','=',$result['komandy'])->first();
                        if(!empty($command)){
                            $buffer_data = "[" . $command->id ."_" . $result['komandy'] . "(";
                        }else{
                            $buffer_data = "[" . "?_" . $result['komandy'] . "(";
                            if(empty($result['avtor_igry'])){
                              $this->absent_command = array_add($this->absent_command, $key ,$result['komandy']);
                              array_push($this->absent_index, $key);
                            }
                        }
                        
                        if(!empty($result['avtor_igry'])){
                            $round_result = 30 + ($result['avtor_igry']*3);
                            $round_results = intval($round_result);
                            $buffer_data .= "Author";

                        }else{
                            for($round_count = 1; $round_count <= $this->model; $round_count++){
                               if(!empty($result[$round_count])){
                                   $round_result = intval($result[$round_count]); 
                               }else{
                                   $round_result = 0; 
                               }
                               if($round_count == 1){
                                    $buffer_data .= $round_result;
                               }else{
                                    $buffer_data .= ";" . $round_result;
                               }
                               $round_results += $round_result;
                            }
                        }    
                        $buffer_data .= ")=>" . $round_results . "]";
                        foreach ($this->global as $key => $value) {
                            if($key == $round_results){
                                $buffer_data = $value . "," . $buffer_data;
                                unset($this->global[$key]);
                            }
                        }
                        $this->global = array_add($this->global, $round_results, $buffer_data);
                    }
                }
                krsort($this->global);
            });
                $count = false;
                foreach ($this->global as $item) {
                   if($count == false){
                        $temp_res->game_data = $item;
                        $count = true;
                   }else{
                        $temp_res->game_data .= "," . $item; 
                   }
                }

                $temp_res->region = config("EnvSettings.REGION");
                $temp_res->save();
           return Response::json(['absent_command'=>$this->absent_command, 'absent_index' => $this->absent_index, 'game_data'=>$temp_res]);     
        }
      return Response::json("None");  
    }


    public function getCommand(){
        $commands = Commands::where('region',config('EnvSettings.REGION'))->get();
        if(count($commands) == 0){
            return Response::json("None");
        }
       return Response::json($commands); 
    }

    public function UpdateCommand(Request $request, $game_id){
        $temp_res = TemporaryResult::where('game_id', '=', $game_id)->where('location_id', '=', $request->pub_id)->first();
        $result = $temp_res->game_data;
        $results = explode(',', $result);
        $count = 0;
        foreach ($results as $result) {
            $buffer = $result;
            $result = explode('(', $result); $rating = $result[1];
            $result = explode('[', $result[0]);$result = explode('_',$result[1]);$result = $result[1];
            if($result == $request->old_command_name){
                $command = Commands::where('command_name', $request->new_command_name)->where('region',config('EnvSettings.REGION'))->first();
                $update_command = "[" . $command->id . "_" . $command->command_name . "(" . $rating;

                if($count == 0){
                   $temp_res->game_data = $update_command;
                   $count++;
                }else{
                   $temp_res->game_data .= "," . $update_command; 
                }
            }else{
                if($count == 0){
                   $temp_res->game_data = $buffer;
                   $count++;
                }else{
                   $temp_res->game_data .= "," . $buffer; 
                }
            }
        }
        $temp_res->save();
        return Response::json("Okey");
    }
    
    public function GetAbsent($game_id, $pub_id){
        $absent_command = [];
        $absent_index = [];
        $auth_redirect = false;
        $key_buf = 0;
        $temp_res = TemporaryResult::where('game_id', '=', $game_id)->where('location_id', '=', $pub_id)->first();
        if(!empty($temp_res)){
            $buf_result = $temp_res->game_data;
            if($buf_result != "Error"){
               $buf_results = explode(',', $buf_result);
               foreach ($buf_results as $key => $buf_result) {
                   $buf_result = explode('(', $buf_result);
                   $author = explode(')', $buf_result[1]);
                   if($author[0] != "Author"){
                        $buf_result  = explode('_', $buf_result[0]);
                        $command_id  = explode('[', $buf_result[0]);
                        if($command_id[1] == "?"){
                            if($auth_redirect == false){
                                $absent_command = array_add($absent_command, $key ,$buf_result[1]);
                                array_push($absent_index, $key);
                            }else{
                                $absent_command = array_add($absent_command, $key_buf ,$buf_result[1]);
                                array_push($absent_index, $key_buf);
                                $key_buf++;
                            }
                            
                        }
                   }else{
                        $auth_redirect = true;
                        $key_buf = $key;
                   } 
                } 
            }
        }
        return Response::json(['absent_command'=>$absent_command, 'absent_index'=>$absent_index]);
    }
}

