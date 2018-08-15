<?php

namespace App\Http\Controllers;

use App\GameBuffer;
use App\ReserveTable;
use App\Location;
use App\CorporateApply;
use Illuminate\Http\Request;
use App\Repositories\RegionRepository;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Config;
use Mail;
use App\User;
use App\Mail\PubQuizCorporates;
use Carbon\Carbon;

class MainPage extends Controller
{
    public function __construct(RegionRepository $regionModel)
    {
        $this->region = $regionModel;  
    }

     public function index()
    {
        $regions = $this->getRegion();
        $games = GameBuffer::where('region', '=', env('REGION'))->where('confirmed', '=', '1')->get();
        $currently_date = Carbon::now();$currently_date = explode(' ', $currently_date);$currently_date = $currently_date[0];
        $date_buffer;$id;
        $count = false;
        $buf_game = 'None';
        foreach ($games as $game) {
            $date = $game->game_date;
            if($date >= $currently_date){
                if($count ==true){
                    if($date_buffer >= $date){
                      $date_buffer = $date;
                      $buf_game = $game;
                    }
                }else{
                  $date_buffer = $date;
                  $buf_game = $game;
                  $count=true;  
                }
            }
        }

        $currently_date = Carbon::now();$currently_date = explode(' ', $currently_date);$currently_date = $currently_date[0];
        
		$closer_games = GameBuffer::where('region', '=', env('REGION'))->where('confirmed', '=', '1')->where('game_date', '>=', $currently_date)->orderBy('game_date', 'asc')->get();
		$games_array = [];
		if(count($closer_games) != 0){
			foreach($closer_games as $cg){
				$all_table = 0;
				$disable_table = 0;
				if($cg->pubs !="None"){
					$all_places = explode(',', $cg->pubs);
					foreach ($all_places as $all_place) {
						$all_place = explode('=', $all_place);
						$all_place = explode(']', $all_place[1]);
						$all_place = $all_place[0];
						$all_table += $all_place;
					}
					array_add($cg, 'all_table', $all_table);
					$RT = ReserveTable::where('id_game', '=', $cg->id)->first();
					$free_places = explode(',', $RT->reserve);
					$pub_array = [];
					foreach ($free_places as $free_place) {
						$pub_id = explode('=>',$free_place);$pub_id = explode('[',$pub_id[0]);$pub_id = $pub_id[1];
						$pub = Location::find($pub_id);
						if(!empty($pub)){
							array_push($pub_array, $pub);
						}
						$free_place = explode('->(', $free_place);$free_place = explode(')]', $free_place[1]);$free_place = $free_place[0];
						if($free_place != "None"){
							$free_place = explode(' | ', $free_place);
							$places = count($free_place);
							$disable_table += $places;
						}
					}
						array_add($cg, 'locations', $pub_array);
						array_add($cg, 'disable_table', $disable_table);
					}else{
						array_add($cg, 'locations', "None");
						array_add($cg, 'all_table', 0);
						array_add($cg, 'disable_table', 0);
					}
				array_push($games_array, $cg);
			}
		}else{
			$games_array = "None";
		}
  		$NVG = view(env('THEME').'.layouts.navigation')->render();
        return view(config('EnvSettings.THEME') . '.MainPage', ['regions'=>$regions, 'closer_game'=>$buf_game, 'url'=>url('Calendar/Game/'), 'closer_games'=>$games_array, 'navigation'=>$NVG]);
    }

    public function showRules(){
    	$regions = $this->getRegion();
    	$Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(env('THEME').'.layouts.navigation')->render();
    	return view(env('THEME').'.Rules', ['region'=>$Rgns, 'navigation'=>$NVG]);
    }

    
    public function showContacts(){
    	$regions = $this->getRegion();
    	$Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(env('THEME').'.layouts.navigation')->render();
    	return view(env('THEME').'.Contacts', ['region'=>$Rgns, 'navigation'=>$NVG]);
    }

    public function CorporateApply(Request $request){
        $corp = new CorporateApply;
        $corp->name = $request->Corporate_name;
        $corp->email = $request->Corporate_email;
        $corp->tel = $request->Corporate_tel;
        $corp->region = config('EnvSettings.REGION');
        $corp->save();

        $admins = User::where('is_admin', 'main_admin')->get();

        if(!empty($admins)){
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new PubQuizCorporates($corp));
            }
        }

        return Response::json($corp);
    }

    public function checkAuth(){
        if(!\Auth::user()){
            return Response::json("Not");
        }else{
            return Response::json("Yes");
        }
    }
}
