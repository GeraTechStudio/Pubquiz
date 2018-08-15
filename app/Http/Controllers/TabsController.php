<?php

namespace App\Http\Controllers;

use App\Location;
use App\Season;
use App\Projects;
use App\GameBuffer;
use App\ReserveTable;
use App\Commands;
use App\GameCommandContents;
use App\TemporaryTeam;
use App\TemporaryResult;
use App\Rating;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Config;
use Carbon\Carbon;

class TabsController extends Controller
{
    public function __construct(RegionRepository $regionModel){ 
        $this->region = $regionModel; 
    }

    public function CalendarGameShow(){
    	$regions = $this->getRegion();
    	$Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
    	$NVG = view(env('THEME').'.layouts.navigation')->render();
    	return view(env('THEME').'.Tabs.GameCalendar', ['region'=>$Rgns, 'navigation'=>$NVG]);

    }

    public function getGames(){
    	$sp_game ="none";
        $games = GameBuffer::where('Region', '=', env('REGION'))->orderBy('game_date', 'asc')->get();
        $date = Carbon::now();$date = explode(' ', $date);$date = $date[0];
        $closer_game = [];
        $last_game = [];
        foreach($games as $game){
             if($game->game_date >= $date && $game->confirmed == 1){
                array_push($closer_game, $game);
             }
             if($game->game_date < $date){
                array_push($last_game, $game);    
            }
        }
        return Response::json(['closer_game' => $closer_game, 'last_game'=>$last_game, 'url'=>url('Calendar/Game/')]);
    }

    public function getCloserGame()
    {
        $games = GameBuffer::where('region', '=', env('REGION'))->where('confirmed', '=', '1')->get();
        $currently_date = Carbon::now();$currently_date = explode(' ', $currently_date);$currently_date = $currently_date[0];
        $date_buffer;$id;
        $count = false;
        $id = 'None';
        foreach ($games as $game) {
            $date = $game->game_date;
            if($date >= $currently_date){
                if($count ==true){
                    if($date_buffer >= $date){
                      $date_buffer = $date;
                      $id = $game->id;
                    }
                }else{
                  $date_buffer = $date;
                  $id = $game->id;
                  $count=true;  
                }
            }
        }
        return Response::json(['id'=>$id, 'url'=>url('Calendar/Game/')]);
    }

    public function ShowGame($game_id){
    	$regions = $this->getRegion();
    	$Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
    	$NVG = view(env('THEME').'.layouts.navigation')->render();
    	$game = GameBuffer::find($game_id);
        if($game->confirmed == 1){
            $table = ReserveTable::where('id_game', '=', $game_id)->get();
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
        	return view(env('THEME').'.Tabs.Game', ['region'=>$Rgns, 'navigation'=>$NVG, 'game'=>$game,'locations'=>$pub_array]);
        }
        if($game->confirmed == 2){
            $table = ReserveTable::where('id_game', '=', $game_id)->get();
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
                        $temp_res = TemporaryResult::where('game_id', '=', $game_id)->where('location_id', '=', $pub_id[1])->first();
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
                $final_rating = Rating::where('game_id', $game_id)->first();
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
                   $temp_res = TemporaryResult::where('game_id', $game_id)->where('location_id', $value->id)->first();
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
         return view(config('EnvSettings.THEME').'.Tabs.Game', ['region'=>$Rgns, 'game'=>$game, 'navigation'=>$NVG, 'locations'=>$pub_array, 'rating'=>$result_array, 'final_rating'=>$final_rating]);
        }
    }

    public function getCommandImg(Request $request, $id_game){
        if(!\Auth::user()){
            $commands = [];
            $close = false;
            foreach ($request->commands as $command) {
                $command = explode('=', $command);
                $command_id = $command[1];
                $command = Commands::find($command_id);
                if(!empty($command)){
                    $players = GameCommandContents::where('command_id', '=', $command_id)->where('game_id','=',$id_game)->get();
                    
                    if(!empty($players[0])){
                        if($players[0]->players == "None"){
                            $command = array_add($command, 'status', "Open");
                            $command = array_add($command, 'places', 0);
                        }else{
                            $players_place = explode(' | ', $players[0]->players); $players_place = count($players_place);
                            if($players_place == 6){
                                $command = array_add($command, 'status', "Full");
                                $command = array_add($command, 'places', 6);
                            }else{
                                $command = array_add($command, 'status', "Open");
                                $command = array_add($command, 'places', $players_place); 
                            }
                        }
                    }      
                       array_push($commands,$command);        
                }
                
            }
            return Response::json(['commands'=>$commands, 'close'=>$close]);    
        }   
        else{
        
            $commands = [];
            $close = false;
            foreach ($request->commands as $command) {
                $command = explode('=', $command);
                $command_id = $command[1];
                $command = Commands::find($command_id);
                if(!empty($command)){
                    $players = GameCommandContents::where('command_id', '=', $command_id)->where('game_id','=',$id_game)->get();
                    
                    if(!empty($players[0])){
                                if($command->id_user == Auth::user()->id){
                                    if($players[0]->players == "None"){
                                        $command = array_add($command, 'status', "Delete");
                                        $command = array_add($command, 'places', 0);
                                    }else{
                                        $players_place = explode(' | ', $players[0]->players); $players_place = count($players_place);

                                        if($players_place == 6){
                                            $command = array_add($command, 'status', "Delete");
                                            $command = array_add($command, 'places', 6);
                                        }else{
                                           $command = array_add($command, 'status', "Delete");
                                           $command = array_add($command, 'places', $players_place);
                                        }
                                    }
                                }else{
                                    if($players[0]->players == "None"){
                                        $command = array_add($command, 'status', "Open");
                                        $command = array_add($command, 'places', 0);
                                    }else{
                                        $players_place = explode(' | ', $players[0]->players); $players_place = count($players_place);

                                        if($players_place == 6){
                                            $command = array_add($command, 'status', "Full");
                                            $command = array_add($command, 'places', 6);
                                        }else{
                                           $command = array_add($command, 'status', "Open");
                                           $command = array_add($command, 'places', $players_place); 
                                        }
                                    }
                                }      
                       array_push($commands,$command);        
                    }
                
                }
                
            }

            return Response::json(['commands'=>$commands, 'close'=>$close]);
        }
    }   

    public function getPub($pub_id){
        $pub = Location::find($pub_id);
        return Response::json($pub);
   }

   public function getCreatedCommandsUser($id_game, GameCommandContents $gameContents){
        if(!\Auth::user()){
            return response('Access Denied')->setStatusCode(401);
        }
        
        $allCommands = Commands::where("id_user", '=', Auth::user()->id)->get();
        $gameContents = $gameContents->where("game_id", "=", $id_game)->get();
        $freeCommands = [];
        $freeCreateCommand = 0;
        $counter = 0;
        if(count($allCommands) != 0){
            if(count($gameContents) != 0){
                foreach($gameContents as $gameContent){
                    $counter++;
                    $allCommands = $allCommands->where("id", "!=", $gameContent->command_id);
                }
                if(count($allCommands) != 0){
                    foreach($allCommands as $Command){
                        array_push($freeCommands, $Command);
                    }
                    $freeCreateCommand = 5- $counter; 

                }else{
                    if($counter<5){
                       $freeCreateCommand = 5- $counter; 
                    }else{
                        $freeCreateCommand = 0;
                    }
                    $freeCommands = "None";
                }
            }else{
                foreach($allCommands as $Command){
                    $counter++;
                    array_push($freeCommands, $Command);
                }
                if($counter<5){
                    $freeCreateCommand = 5- $counter; 
                }else{
                    $freeCreateCommand = 0;
                }
            } 
        }else{
            $freeCommands = "None";
            $freeCreateCommand = 5;
        }

        $temporary_commands = TemporaryTeam::where('user_id', Auth::user()->id)->get();
        if(count($temporary_commands) != 0){
            $counter = 0;
            if($freeCommands == "None"){
                $freeCommands = [];
            }
            foreach($gameContents as $gameContent){
                $counter++;
                $temporary_commands = $temporary_commands->where("command_id", "!=", $gameContent->command_id);
            }
            if(count($temporary_commands) != 0){
                foreach($temporary_commands as $temporary_command){
                    $allCommand = Commands::find($temporary_command->command_id);
                    if(!empty($allCommand)){
                        array_push($freeCommands, $allCommand);  
                    }
                }
                if( $freeCreateCommand == 5){
                   $freeCreateCommand = 5 - $counter; 
                } 
            }
        }
        if(empty($freeCommands)){
            $freeCommands = "None";
            $freeCreateCommand = 5; 
        }

        return Response::json(["freeCommands"=> $freeCommands, "freeCreateCommand"=>$freeCreateCommand]);
   }

   public function addCommand(Request $request, $id_game){
    if(!\Auth::user()){
            return response('Access Denied')->setStatusCode(401);
    }
    $table = ReserveTable::where('id_game', '=', $id_game)->first();
    $count = 0;
    $attribute = 0;
    if(!empty($table)){
        $pubs = explode(',', $table->reserve);
        foreach ($pubs as $pub){
            $pub_part = explode('=>', $pub); $pub_id = explode('[', $pub_part[0]); $pub_id = $pub_id[1];
            if($pub_id == $request->Pub_id){
                $pub_commands = explode('->', $pub_part[1]); $pub_free_places = $pub_commands[0]; $pub_commands = explode(']', $pub_commands[1]); $pub_commands = explode('(', $pub_commands[0]); $pub_commands = explode(')', $pub_commands[1]); $pub_commands = $pub_commands[0];

                $command = Commands::find($request->command_id);
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

    $game_contents = new GameCommandContents;
    $game_contents->game_id = $id_game;
    $game_contents->command_id = $request->command_id;
    $game_contents->players = "". Auth::user()->login . "=" . Auth::user()->id;
    $game_contents->buffer_players = "None";
    $game_contents->region  = env("REGION");
    $game_contents->save();

    $Busy_places = explode(" | ", $attribute); $Busy_places = count($Busy_places);
    return Response::json(['attribute'=>$attribute, 'pub_free_places'=>$pub_free_places, 'Busy_places'=>$Busy_places]);
   }


   public function cancelCommand(Request $request, $game_id){
    

    $table = ReserveTable::where('id_game', '=', $game_id)->first();
    $count = 0;
    $attribute = "None";
    if(!empty($table)){
       $tables = explode(",", $table->reserve);
       foreach ($tables as $buf_table) {
            $table_part = explode('=>', $buf_table);
            $table_id = explode('[', $table_part[0]); $table_id = $table_id[1];

            if($request->Pub_id == $table_id){
                $commands = explode('->', $table_part[1]);$commands = explode(']', $commands[1]);$commands = explode('(', $commands[0]);$commands = explode('(', $commands[1]);$commands = explode(')', $commands[0]);
                $update_command = "None";
                $count_command = 0;
                if($commands[0] != "None"){
                    $commands = explode(' | ', $commands[0]);
                    foreach ($commands as $command) {
                        $command_id = explode('=', $command); $command_id = $command_id[1];
                        if($command_id != $request->command_id){
                            if($count_command == 0){
                                $update_command = $command;
                                $attribute = $command;
                                $count_command++;
                            }else{
                                $update_command .= " | " . $command;
                                $count_command++;
                                $attribute .= " | " . $command;
                            }
                        }
                    }  
                }
                $places = explode('->', $table_part[1]); $places = $places[0];

                if($count == 0){
                   $table->reserve = "[" . $table_id . "=>" . $places . "->(" . $update_command . ")]";
                   $count++;
                }else{
                   $table->reserve = "" . $table->reserve . ",[" . $table_id . "=>" . $places . "->(" . $update_command . ")]";
                } 
                

            }else{

               if($count == 0){
                   $table->reserve = "" . $buf_table;
                   $count++;
                }else{
                   $table->reserve = "". $table->reserve . "," . $buf_table; 
                }

            }
  
        }
    }
    $table->save();

    $contents = GameCommandContents::where('game_id', '=', $game_id)->where('command_id', '=', $request->command_id)->get();
    if(count($contents) != 0){
        foreach ($contents as $cont) {
            $cont->delete();
        }
    }
    return Response::json(["places" => $places, "table"=>$count_command, "attribute"=>$attribute]);
   }

   public function applyinCommand(Request $request, $game_id){
    if(!\Auth::user()){
        return response('Access Denied')->setStatusCode(401);
    }
    $contents = GameCommandContents::where('game_id', '=', $game_id)->where('command_id', '=', $request->command_id)->get();
        if(count($contents) != 0){
            foreach ($contents as $content) {
                if($content->buffer_players == "None"){
                    $content->buffer_players = Auth::user()->login . "=" . Auth::user()->id;
                    $content->save();
                }else{
                    $allow_add = true;
                    $buffer_players = explode(' | ', $content->buffer_players);
                    foreach ($buffer_players as $buffer_player) {
                        $buffer_player_id = explode('=', $buffer_player);$buffer_player_id = $buffer_player_id[1];
                        if($buffer_player_id == Auth::user()->id){
                            $allow_add = false;
                        }
                    }
                    if($allow_add == true){
                        $content->buffer_players .= " | " . Auth::user()->login . "=" . Auth::user()->id;
                        $content->save();
                    }
                }
               
            }
            
        }
        
    return Response::json($request);
   }
}

