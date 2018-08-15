<?php

namespace App\Http\Controllers;

use Config;
use App\Rating;
use App\Season;
use App\Projects;
use App\Commands;
use App\GameBuffer;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;

class GlobalRating extends Controller
{
    public function __construct(RegionRepository $regionModel)
    {
        $this->region = $regionModel;  
    }

    public function showRatingForm(){
    	$regions = $this->getRegion();
    	$Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(env('THEME').'.layouts.navigation')->render();
        $seasons = Season::all();
        	if(count($seasons) == 0){
        		$seasons = [];	
        	}
        $projects = Projects::all();
        	if(count($projects) == 0){
        		$projects = [];	
        }


    	return view(env('THEME').'.Rating', ['region'=>$Rgns, 'navigation'=>$NVG, 'empty' => true, 'seasons'=>$seasons, 'projects'=>$projects]);
    }

    public function showRating(Request $request){
    	$regions = $this->getRegion();
    	$Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(env('THEME').'.layouts.navigation')->render();
        $seasons = Season::all();
        	if(count($seasons) == 0){
        		$seasons = [];	
        	}
        $projects = Projects::all();
        	if(count($projects) == 0){
        		$projects = [];	
        	}

        $empty = false;

        $ratings = Rating::where('id_project', $request->Project)->where('id_season', $request->Season)->where('game_order', 0)->get();	
       	$rating_array = [];

       	foreach($ratings as $rating) {
       		$game = GameBuffer::find($rating->game_id);
       		if(!empty($game)){
       			$rating_array = array_add($rating_array, $game->game_date, $rating);
       		}
       	}
       	$commands_array = [];
       	$amount_rating_loop = 0;
       	$array_rating_loop = [];
       	if(!empty($rating_array)){
       		ksort($rating_array);$amount_rating_loop = count($rating_array);
       		$first_load = true;
       		$step = 0;
       		foreach($rating_array as $rating){
       			$buffer_commands = explode(',', $rating->game_data);
       			$places = 1;
       			array_push($array_rating_loop,$rating->game_id);
	       		foreach ($buffer_commands as $buffer_command){
	       			$buffer_command = explode('[', $buffer_command);
	       			$buffer_command = explode('(', $buffer_command[1]);
	       			$buffer_command = explode('_', $buffer_command[0]);
	       			$command_id = $buffer_command[0];
	       			$command = Commands::find($command_id);
	       			if(!empty($command)){
	       				$key = $command->id . "_" . $command->command_name;
	       			}else{
	       				$key = "<#". $command_id . "_" . $buffer_command[1];
	       			}
	       			if($places <= 40){
	       				$result = 51 - $places;
	       			}else{
	       				$result = 10;
	       			}

	       			if($first_load == true){
	       				$value = $step . "->" . $rating->game_id . "(" . $result . ")";
	       				$commands_array = array_add($commands_array, $key, $value);
	       			}else{
	       				foreach ($commands_array as $command_key => $command_value) {
	       					if($command_key == $key){
	       						$buff_value = $command_value . ";" . $step . "->" . $rating->game_id . "(" . $result . ")";
	       						unset($commands_array[$key]);
	       					}else{
	       						$buff_value = $step . "->" . $rating->game_id . "(" . $result . ")";
	       					}
	       					$commands_array = array_add($commands_array, $key, $buff_value);
	       				}
	       			}
	       			$places++;	
		       	}
		       	$first_load = false;
		       	$step++;	
	       	}
       		
       	}else{
       		$empty = true;
       	}
       	$global_command_array = [];
       	$final_part_result = [];
       	$index_array = [];
       	$add_addition_est = 0;
       	if(!empty($commands_array)){
       		foreach ($commands_array as $key_command => $value_command) {
       			$global_result_array = [];
       			$total_result = [];
       			$comand_results = explode(';', $value_command);
       			foreach ($comand_results as $comand_result) {
       				$step_command_game = explode('->', $comand_result);
       				$global_result_array = array_add($global_result_array, $step_command_game[0], $comand_result);
       			}
       			$buffer_array = [];	
       			$difference_global = array_diff_key($array_rating_loop, $global_result_array);
       	
				foreach($difference_global as $key=>$global_command){
					$buffer_array = array_add($buffer_array, $key, $key . "->" . $global_command . "(0)");
					array_push($total_result, 0);
				}
				   
				foreach ($global_result_array as $key => $value) {
					$buffer_array = array_add($buffer_array, $key, $value);
					$value = explode('(', $value); $value = explode(')', $value[1]);
					array_push($total_result, intval($value[0]));
				}
				sort($buffer_array);
				sort($total_result);

				$min_total = 0;
				$max_total = 0;
				if(count($total_result) != 0){
					if(count($total_result) > 2){
						foreach ($total_result as $key => $value) {
							if($key > 1){
								$min_total += $value;
							}
							$max_total += $value;
						}
					}else{
            foreach ($total_result as $key => $value) {
              $min_total += $value;
              $max_total += $value;
          }
				}
      }
				$step = false;
				$new_game_result = "";
				foreach ($buffer_array as $key => $value) {
					if($step == false){
       					$new_game_result = $value;
       					$step = true;
					}else{
       					$new_game_result .= ";" . $value;	
       				}	
				}
				$new_game_result .= "=>" . $max_total . ":" . $min_total;
				
				$min_total = $min_total;
				if(in_array($min_total, $index_array)){
					$add_addition_est += 1;
					$min_total .= "_" . $add_addition_est;
				}
				array_push($index_array, $min_total);

				  
				$final_part_result = array_add($final_part_result, $min_total, $new_game_result);
				$global_command_array = array_add($global_command_array, $min_total, $key_command);

			}		
		}
		rsort($index_array);
		$date_array = [];

       	foreach($ratings as $rating) {
       		$game = GameBuffer::find($rating->game_id);
       		if(!empty($game)){
       			$date_array = array_add($date_array, $game->id, $game->game_date);
       		}
       	}

		
    	return view(env('THEME').'.Rating', ['region'=>$Rgns, 'navigation'=>$NVG, 'seasons'=>$seasons, 'projects'=>$projects,'empty'=>$empty, 'index_array'=>$index_array, 'command'=>$global_command_array, 'result'=>$final_part_result, 'date_array'=>$date_array]);
    }
}
