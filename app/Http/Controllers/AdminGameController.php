<?php

namespace App\Http\Controllers\Admin;

use App\Season;
use App\Projects;
use App\GameBuffer;
use App\ReserveTable;
use Carbon\Carbon;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Config;


class AdminGameController extends Controller
{
    public function __construct(RegionRepository $regionModel){ 
        $this->region = $regionModel;  
    }

    public function showCalendar(){
    	$regions = $this->getRegion();
    	$Rgns = view(config('EnvSettings.THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(config('EnvSettings.THEME').'.layouts.navigation')->render();
    	return view(config('EnvSettings.THEME').'.Admin.GameCalendar', ['region'=>$Rgns, 'navigation'=>$NVG]);
    }
    
    public function getCalendarData(){
        $sp_game ="none";
        $games = GameBuffer::where('Region', '=',config('EnvSettings.REGION'))->orderBy('game_date', 'asc')->get();
        $date = Carbon::now();$date = explode(' ', $date);$date = $date[0];
        $closer_game = [];
        $last_game = [];
        foreach($games as $game){
             if($game->game_date >= $date && $game->confirmed != 2){
                array_push($closer_game, $game);
             }
             if($game->game_date < $date || $game->confirmed==2){
                $game->confirmed = 2;
                $game->save();
                array_push($last_game, $game);    
            }
        }
        return Response::json(['closer_game' => $closer_game, 'last_game'=>$last_game, 'url'=>url('admin/game/creation/')]);
    }




    public function getResources(){
        $projects = Projects::where('region', '=',config('EnvSettings.REGION'))->get();
        $seasons = Season::where('region', '=',config('EnvSettings.REGION'))->get();
        $array = ['projects'=>$projects, 'seasons'=>$seasons];
        return Response::json($array);
    }

    public function postGameBuffer(Request $request){
    $Game_project_name = Projects::find($request->Game_project);
    $Game_season_name = Season::find($request->Game_season);

        $buffer = new GameBuffer;
        $buffer->game_name = $request->Game_name;
        $buffer->game_date = $request->Game_data;
        $buffer->game_desc = 'None';
        $buffer->pubs = 'None';
        $buffer->region =config('EnvSettings.REGION');
        $buffer->id_project = $request->Game_project;
        $buffer->project_name = $Game_project_name->Project_name;
        $buffer->project_color = $Game_project_name->Project_color;
        $buffer->id_season = $request->Game_season;
        $buffer->season_name = $Game_season_name->Season_name;
        $buffer->save();

        $table = new ReserveTable;
        $table->id_game = $buffer->id;
        $table->reserve = "None";
        $table->region = env("REGION");
        $table->save();
        return Response::json($buffer);
    }

    public function getCloserGame()
    {
        $games = GameBuffer::where('region', '=',config('EnvSettings.REGION'))->where('confirmed', '=', '1')->get();
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
        return Response::json(['id'=>$id, 'url'=>url('admin/game/creation/')]);
    }
    
}
