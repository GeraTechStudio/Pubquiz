<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Projects;
use App\Season;
use App\TemporaryResult;
use App\Commands;
use App\CorporateApply;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Excel;

use App\GameBuffer;
class AdminHomePageController extends Controller
{


    public function __construct(RegionRepository $regionModel){ 
        $this->region = $regionModel; 
        $this->global = [];
        $this->model = "";
        $this->all_users = 0; 
    }

    public function showPage(){
    	$array_page = [];
    	$regions = $this->getRegion();
    	$admins = User::where('is_admin', '=', config('EnvSettings.REGION')."_admin")->where('region', '=',config('EnvSettings.REGION'))->take(25)->get();
    	$admin_pages = count($admins)%25;
    	if($admin_pages == 0){
    		$admin_pages = count($admins)/25;
    	}
    	$array_page = array_add($array_page, 'admin_pages', $admin_pages);
    	
    	$users = User::where('is_admin', '!=', config('EnvSettings.REGION')."_admin")->where('is_admin', '!=', 'main_admin')->where('region', '=', config('EnvSettings.REGION'))->get();
    	$this->all_users = count($users);
    	$user_pages = intval(count($users)/25)+1;
    	if($user_pages == 0){
    		$user_pages = count($users)/25;
    	}
    	$array_page = array_add($array_page, 'user_pages', $user_pages);
    	$users = $users->take(25);

        $corporates = CorporateApply::orderBy('created_at', 'desc')->where('region', '=',config('EnvSettings.REGION'))->take(25)->get();

        $commands = Commands::where('region', '=',config('EnvSettings.REGION'))->take(25)->get();
        $projects = Projects::where('region', '=',config('EnvSettings.REGION'))->get();
        $seasons = Season::where('region', '=',config('EnvSettings.REGION'))->get();
        $Rgns = view(config('EnvSettings.THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(config('EnvSettings.THEME').'.layouts.navigation')->render();
    	return view(config('EnvSettings.THEME').'.Admin.admin_home', ['users'=>$users, 'admins'=>$admins, 'commands'=>$commands, 'corporates'=>$corporates, 'array_page'=>$array_page, 'count_users'=>$this->all_users, 'region'=>$Rgns, 'projects' => $projects, 'seasons'=>$seasons, 'navigation'=>$NVG]);
    }

    public function changeAdminStatus(Request $request, $admin_id){

    	$notadmin = User::where('id', '=', $admin_id)->first();
    	$notadmin->is_admin = $request->is_admin;
    	$notadmin->save();
    	return Response::json($notadmin);
    }

    public function showAdmin($admin_id){

    	$admin = User::find($admin_id);
    	return Response::json($admin);
    }

    public function changeAdmin(Request $request, $admin_id){

    	$admin = User::find($admin_id);

    	$admin->name = $request->name;
    	if(!empty($request->password)){
    		$admin->password = bcrypt($request->password);
    	}
    	$admin->verified = $request->verified;
    	$admin->save();
    	return Response::json($admin);
    }

    public function deleteAdmin($admin_id){

    	$admin = User::destroy($admin_id);
    	return Response::json($admin);
    }


    public function changeUserStatus($user_id){

    	$notuser = User::where('id', '=', $user_id)->first();
    	$notuser->is_admin = config('EnvSettings.REGION')."_admin";
    	$notuser->save();
    	return Response::json($notuser);
    }

    public function showUser($user_id){

    	$user = User::find($user_id);
    	return Response::json($user);
    }

    public function changeUser(Request $request, $user_id){

    	$user = User::find($user_id);

    	$user->name = $request->name;
    	if(!empty($request->password)){
    		$user->password = bcrypt($request->password);
    	}
    	$user->verified = $request->verified;
    	$user->save();
    	return Response::json($user);
    }

    public function deleteUser($user_id){

    	$user = User::destroy($user_id);
    	return Response::json($user);
    }
    
    public function addProject(Request $request){
        $project = new Projects;
        $project->Project_name = $request->project_name;
        $project->Project_color = $request->project_color;
        $project->region = config('EnvSettings.REGION');
        $project->save();
        $ID = Projects::orderBy('created_at', 'desc')->first()->id;
        return Response::json($ID);
    }   

    public  function getProjectID($project_id){
        $project = Projects::find($project_id);
        return $project;
    }

    public function deleteProjectID($project_id){

        $project = Projects::destroy($project_id);
        return Response::json($project);
    }

    public function changeProjectID(Request $request, $project_id){

        $project = Projects::find($project_id);

        $project->Project_name = $request->Project_name;
        $project->Project_color = $request->Project_color;
        $project->save();

        return Response::json($project);
    }


    public function addSeason(Request $request){
        $season = new Season;
        $season->Season_name = $request->season_name;
        $season->region = config('EnvSettings.REGION');
        $season->save();
        $ID = Season::orderBy('created_at', 'desc')->first()->id;
        return Response::json($ID);
    }   

    public  function getSeasonID($season_id){
        $season = Season::find($season_id);
        return $season;
    }

    public function deleteSeasonID($season_id){

        $season = Season::destroy($season_id);
        return Response::json($season);
    }

    public function changeSeasonID(Request $request, $season_id){

        $season = Season::find($season_id);

        $season->Season_name = $request->Season_name;
        $season->save();

        return Response::json($season);
    }

    public function indexfile(){
        $regions = $this->getRegion();
        $Rgns = view(config('EnvSettings.THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $NVG = view(config('EnvSettings.THEME').'.layouts.navigation')->render();
        $game = GameBuffer::find(1);
        return view('file');
    }

    public function getFile(Request $request){
        $path = "";
        
        if($request->hasFile('exel')){
            $temp_res = new TemporaryResult;
            $temp_res->game_id = 1;
            $temp_res->location_id = 1;
            $temp_res->round_size = 9;
            $temp_res->game_data = "Error";
            $this->model = $temp_res->round_size;
            $this->global = [];

            $path = $request->file('exel')->getRealPath();
            $data = Excel::load($path, function($reader) {
                $results = $reader->toArray();
                foreach ($results as $result) {
                    // dd($results);
                    $round_results = 0;
                    if(!empty($result['komandy'])){
                        $command = Commands::where('region', '=', config('EnvSettings.REGION'))->where('command_name','=',$result['komandy'])->first();
                        if(!empty($command)){
                            $buffer_data = "[" . $command->id ."_" . $result['komandy'] . "(";
                        }else{
                            $buffer_data = "[" . "?_" . $result['komandy'] . "(";
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
                return Response::json($temp_res);
        }
    }







    public function ToolbarOrder(Request $request){
    	$step = ($request->carrently_page - 1) * $request->carrently_ammount_page;
    	switch ($request->toolbar) {
    		case 'User':
    			$users = User::where('is_admin', '!=', config('EnvSettings.REGION')."_admin")->where('is_admin', '!=', 'main_admin')->where('region', '=', config('EnvSettings.REGION'))->skip($step)->take($request->carrently_ammount_page)->orderBy('id', $request->toggle)->get();
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    	return Response::json($users);
    }

    public function ToolbarGetUsers(){
    	$users = User::where('is_admin', '!=', config('EnvSettings.REGION')."_admin")->where('is_admin', '!=', 'main_admin')->where('region', '=', config('EnvSettings.REGION'))->get();
    	return Response::json($users);
    }
    public function ToolbarGetUser(Request $request){
    	$user = User::where('login', $request->input)->get();
    	return Response::json($user);
    }


}
