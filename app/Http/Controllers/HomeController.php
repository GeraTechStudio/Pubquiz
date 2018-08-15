<?php

namespace App\Http\Controllers;

use App\User;
use App\Location;
use Carbon\Carbon;
use App\Commands;
use App\GameBuffer;
use App\GameCommandContents;
use App\ReserveTable;
use App\TemporaryTeam;
use App\CommandsApply;
use Illuminate\Http\Request;
use App\Repositories\RegionRepository;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Config;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RegionRepository $regionModel)
    {
        $this->middleware('auth');
        $this->middleware('redirect_admin');
        $this->region = $regionModel;  
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function main()
    {
        $regions = $this->getRegion();
        $Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $commands = Commands::where('id_user', '=', \Auth::user()->id)->where('region', '=', env('REGION'))->orderBy('count', 'asc')->get();

        $new_commands = [];
        if(count($commands) != 0){
           foreach ($commands as $command) {
            $command_applyers = 0;
                $applyers = CommandsApply::where('command_id', '=', $command->id)->where('status', "0")->get();
                $count_apply = count($applyers);
                $command = array_add($command, 'applyers', $count_apply);
            array_push($new_commands, $command);
           } 
        }

        $temporary_commands = [];
        $temporary_command = TemporaryTeam::where('user_id', '=', \Auth::user()->id)->where('region', '=', config('EnvSettings.REGION'))->get();
        if(count($temporary_command) != 0){
            foreach ($temporary_command as $value) {
                $command_temp = Commands::where('id',$value->command_id)->first();
                if(!empty($command_temp)){
                   array_push($temporary_commands, $command_temp);  
                }  
            }    
        }

        
        $currently_date = Carbon::now();$currently_date = explode(' ', $currently_date);$currently_date = $currently_date[0];
        
        // $closer_games = GameBuffer::where('region', '=', env('REGION'))->where('confirmed', '=', '1')->where('game_date', '>=', $currently_date)->orderBy('game_date', 'asc')->get();
            $games_array = [];
            // $count = 0;
            // foreach($closer_games as $cg){
            //     if($count <2){
            //            $all_table = 0;
            //            $disable_table = 0;
            //                 if($cg->pubs !="None"){
            //                     $all_places = explode(',', $cg->pubs);
            //                     foreach ($all_places as $all_place) {
            //                         $all_place = explode('=', $all_place);
            //                         $all_place = explode(']', $all_place[1]);
            //                         $all_place = $all_place[0];
            //                         $all_table += $all_place;
            //                     }
            //                     array_add($cg, 'all_table', $all_table);
                             
            //                     $RT = ReserveTable::where('id_game', '=', $cg->id)->first();
            //                     $free_places = explode(',', $RT->reserve);
            //                     $pub_array = [];
            //                     foreach ($free_places as $free_place) {
            //                         $pub_id = explode('=>',$free_place);$pub_id = explode('[',$pub_id[0]);$pub_id = $pub_id[1];
            //                         $pub = Location::find($pub_id);
            //                         if(!empty($pub)){
            //                            array_push($pub_array, $pub); 
            //                         }
            //                         $free_place = explode('->(', $free_place);$free_place = explode(')]', $free_place[1]);$free_place = $free_place[0];
            //                         if($free_place != "None"){
            //                             $free_place = explode(' | ', $free_place);
            //                             $places = count($free_place);
            //                             $disable_table += $places;
            //                         }  
            //                     }
            //                     array_add($cg, 'locations', $pub_array);
            //                     array_add($cg, 'disable_table', $disable_table);  
            //                 }else{
            //                     array_add($cg, 'locations', "None");
            //                    array_add($cg, 'all_table', 0);
            //                    array_add($cg, 'disable_table', 0); 
            //                 }

            //                 array_push($games_array, $cg);
            //     $count++;    
            //     }
            // }
        
        $my_commands = $commands = Commands::where('id_user', '=', \Auth::user()->id)->where('region', '=', env('REGION'))->orderBy('count', 'asc')->get();
        $games = [];
        if(count($my_commands) != 0){
            foreach($my_commands as $my_command){
                $gccs = GameCommandContents::where('command_id', '=', $my_command->id)->first();
                if(!empty($gccs)){
                    $game = GameBuffer::find($gccs->game_id);
                    if(!empty($game)){
                        array_push($games, $game);
                    }
                    
                }
            }
        }

        $my_temporary_commands = TemporaryTeam::where('user_id', '=', \Auth::user()->id)->where('region', '=', config('EnvSettings.REGION'))->get();
        if(count($my_temporary_commands) != 0){
            foreach($my_temporary_commands as $my_temporary_command){
                $my_temporary_command = Commands::find($my_temporary_command->command_id);
                if(!empty($my_temporary_command)){
                    $gccs = GameCommandContents::where('command_id', '=', $my_temporary_command->id)->get();
                    if(!empty($gccs)){
                        $early_game = [];
                        $count_early_game = 0;
                        foreach ($gccs as $gcc) {
                           $game = GameBuffer::find($gcc->game_id);
                            if(!empty($game)){
                                if($count_early_game == 0){
                                    if($currently_date <= $game->game_date){
                                        $early_game = $game;
                                        $count_early_game++;
                                    }
                                }else{
                                    if($early_game->game_date > $game->game_date && $currently_date <= $game->game_date){
                                        dd($early_game->game_date, $game->game_date,$early_game->game_date > $game->game_date);
                                       $early_game->game_date = $game->game_date; 
                                    }
                                }
                            }
                        }
                        if(!empty($early_game)){
                            array_push($games, $early_game);
                        }
                        

                    }
                }
            }
        }
        if(!empty($games)){
            $date_buffer;$id;
            $count = false;
            $my_closer_game = 'None';
            foreach ($games as $game) {
                $date = $game->game_date;
                if($date >= $currently_date){

                    if($count ==true){
                        if($date_buffer >= $date){
                          $date_buffer = $date;
                          $my_closer_game = $game;
                        }
                    }else{
                      $date_buffer = $date;
                      $my_closer_game = $game;
                      $count=true;  
                    }
                }
            }
        }
        else{
            $my_closer_game = "None";
        }

        $temporary_applys = CommandsApply::where('user_id', Auth::user()->id)->get();
        $applyers = [];
        if(count($temporary_applys) != 0){
            foreach ($temporary_applys as $temporary_apply) {
                $command = Commands::where('id', $temporary_apply->command_id)->first();
                $temporary_apply = array_add($temporary_apply, 'command_name', $command->command_name);
                array_push($applyers, $temporary_apply);
            }   
            
        }

        $NVG = view(env('THEME').'.layouts.navigation')->render();
        return view('home', ['region'=>$Rgns, 'commands'=>$new_commands, 'closer_games'=>$games_array, 'my_closer_game'=>$my_closer_game, 'navigation'=>$NVG, 'temporary_commands'=>$temporary_commands, 'applyers'=>$applyers]);
    }

    public function getUser($user_id)
    {
        $user = User::find($user_id);
        return Response::json($user);
    }

    public function changeUser(Request $request, $user_id){

        $user = User::find($user_id);

        $user->name = $request->name;
        if(!empty($request->password)){
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return Response::json($user);
    }


    public function uploadAvatar(Request $request)
    {
        $user = User::find($request->user_id);

        if($request->hasFile('Avatar')) {
            $file = $request->file('Avatar');
            if($user->user_img_name == "None"){
                $file_name = "" . $request->user_id . $file->getClientOriginalName();
                $img_path = url('storage') . "/User_img/";
                $file->move(storage_path('app/public/'.Config::get('settings.User_img_path')),$file_name);
                $user->user_img_name = $file_name;
                $user->user_img_path  = "". $img_path . $file_name;
                $user->save();
                $Answer = ['img_path' => $user->user_img_path];
            }
            else{
                unlink(storage_path('app/public/' . Config::get('settings.User_img_path') . "/" . $user->user_img_name));
                $file_name = "" . $request->user_id . $file->getClientOriginalName();
                $img_path = url('storage') . "/User_img/";
                $file->move(storage_path('app/public/'.Config::get('settings.User_img_path')),$file_name);
                $user->user_img_name = $file_name;
                $user->user_img_path  = "". $img_path . $file_name;
                $user->save();
                $Answer = ['img_path' => $user->user_img_path];
            }

        }
        else{
            if($user->user_img_name == "None"){
                
                $Answer = ['img_path' => "None"];
            }
            else{
                $Answer = ['img_path' => $user->user_img_path];
            }
        }   
        return Response::json($Answer);
    }

    public function UniqueCommand(Request $request){
        $command = Commands::where('command_name', '=', $request->Command_name_val)->where('region', '=', env('REGION'))->get();
        return Response::json($command); 
    }

    public function createCommand(Request $request){
        $command = new Commands;
        $command->id_user = $request->user_id;
        $command->count = $request->count;
        $command->command_name = $request->Command_name_val;
        $command->region = env('REGION');
        $command->save();
        return Response::json(['command'=>$command, 'url'=>route('showMyCommand')]);
    }

    public function uploadCommandAvatar(Request $request){
        $command = Commands::where('command_name', '=', $request->command_name)->where('region', '=', env('REGION'))->get();
        $command = $command[0];
        if($request->hasFile('Avatar')) {
            $file = $request->file('Avatar');
            if($command->command_img_name == "None"){
                $file_name = "" . $request->user_id . "-" . $request->count_command . $file->getClientOriginalName();
                $img_path = url('storage') . "/Command_img/";
                $file->move(storage_path('app/public/'.Config::get('settings.Command_img_path')),$file_name);
                $command->command_img_name = $file_name;
                $command->command_img_path  = "". $img_path . $file_name;
                $command->save();
                $Answer = ['img_path' => $command->command_img_path];
            }
            else{
                unlink(storage_path('app/public/' . Config::get('settings.Command_img_path') . "/" . $command->command_img_name));
                $file_name = "" . $request->user_id . "-" . $request->count_command . $file->getClientOriginalName();
                $img_path = url('storage') . "/Command_img/";
                $file->move(storage_path('app/public/'.Config::get('settings.Command_img_path')),$file_name);
                $command->command_img_name = $file_name;
                $command->command_img_path  = "". $img_path . $file_name;
                $command->save();
                $Answer = ['img_path' => $command->command_img_path];
            }

        }
        else{
            if($command->command_img_name == "None"){
                
                $Answer = ['img_path' => "None"];
            }
            else{
                $Answer = ['img_path' => $command->command_img_path];
            }
        }   
        return Response::json($Answer);
    }

    public function changeCommand(Request $request){
        $command = Commands::where('id_user', '=', $request->user_id)->where('count', '=', $request->count)->where('region', '=', env('REGION'))->get();
        $command = $command[0];
        $command->command_name = $request->Command_name_val;
        $command->save();
        return Response::json(['command'=>$command, 'url'=>route('showMyCommand')]);
    }


    public function deleteCommand(Request $request){
        $command = Commands::destroy($request->id);
        $gccs = GameCommandContents::where('command_id','=', $request->id)->get();
        if(count($gccs)!=0){
            foreach ($gccs as $gcc) {
                $gcc->delete();
            }
        }

        $temporary_applys = CommandsApply::where('command_id', $request->id)->get();
        if(count($temporary_applys)!=0){
            foreach ($temporary_applys as $gcc) {
                $gcc->delete();
            }
        }
        $TT = TemporaryTeam::where('command_id', $request->id)->get();
        if(count($TT)!=0){
            foreach ($TT as $gcc) {
                $gcc->delete();
            }
        }

        return Response::json($command);
    }

    public function showMyCommand($command_id){
        $regions = $this->getRegion();
        $Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
        $gccs = GameCommandContents::where('command_id', '=', $command_id)->get();
        $command = Commands::find($command_id);
        if(!empty($command)){        
            if(count($gccs) !=0 ){
               $games_array = [];
                foreach ($gccs as $gcc) {
                    $all_table = 0;
                    $disable_table = 0;
                    $game_id = $gcc->game_id;
                    $gb = GameBuffer::find($game_id);
                    if($gb->pubs !="None"){
                        $all_places = explode(',', $gb->pubs);
                        foreach ($all_places as $all_place) {
                            $all_place = explode('=', $all_place);
                            $all_place = explode(']', $all_place[1]);
                            $all_place = $all_place[0];
                            $all_table += $all_place;
                        }
                        array_add($gb, 'all_table', $all_table);
                     
                        $RT = ReserveTable::where('id_game', '=', $game_id)->first();
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
                        array_add($gb, 'locations', $pub_array);
                        array_add($gb, 'disable_table', $disable_table);  
                        $command_applyers = 0;
                        $buffer_players = GameCommandContents::where('command_id', '=', $command->id)->where('game_id','=',$game_id)->get();
                        if(count($buffer_players) != 0){
                            foreach($buffer_players as $buffer_player){
                                $buffer_player = $buffer_player->buffer_players;
                                if($buffer_player != "None"){
                                    $buffer_player = explode(' | ',$buffer_player);
                                    $command_applyers += count($buffer_player);
                                }
                            }
                        }
                        array_add($gb, 'applyers', $command_applyers);
                    }else{
                        array_add($gb, 'locations', "None");
                       array_add($gb, 'all_table', 0);
                       array_add($gb, 'disable_table', 0);
                       array_add($gb, 'applyers', 0); 
                    }
                    array_push($games_array, $gb);
                } 
            }else{
                $games_array = "None";
            }
            $NVG = view(env('THEME').'.layouts.navigation')->render();
            return view('PubQuiz.Home.myCommand', ['region'=>$Rgns, 'games'=>$games_array, 'command'=>$command,'navigation'=>$NVG]);
        }else{
            return redirect()->route('home');
        }
    }

    public function getPub($Pub_id){
        $location = Location::find($Pub_id);
        return Response::json($location);
    }

    public function getCommandConsist($command_id){
        $commandsConsists = [];
        $commandor = Commands::where('id',$command_id)->first();
        if(!empty($commandor)){
            $commandor = User::find($commandor->id_user);
            if(!empty($commandor)){
                array_push($commandsConsists, $commandor);
            }
        }
        $users = TemporaryTeam::where('command_id', $command_id)->get();
            if(count($users) != 0){
                foreach ($users as $user) {
                    $user = User::find($user->user_id);
                    if(!empty($user)){
                        array_push($commandsConsists, $user);
                    }
                }
                
            }
        $TemporaryCommandsConsists = [];    
        $temporary_commands = CommandsApply::where('command_id', $command_id)->where('status', '0')->get();
            if(count($temporary_commands) != 0){
                foreach ($temporary_commands as $user) {
                    $user = User::find($user->user_id);
                    if(!empty($user)){
                        array_push($TemporaryCommandsConsists, $user);
                    }
                }
                
            }
        return Response::json(['consist'=>$commandsConsists, 'apply'=>$TemporaryCommandsConsists]); 
    }

    public function applyUser(Request $request){
        $temporary_commands = CommandsApply::where('command_id', $request->command_id)->where('user_id', $request->user_id)->first();
        if(!empty($temporary_commands)){
            $user = User::find($temporary_commands->user_id);
            if(!empty($user)){
                $temp = new TemporaryTeam;
                $temp->user_id = $temporary_commands->user_id;
                $temp->command_id = $temporary_commands->command_id;
                $temp->region = config('EnvSettings.REGION');
                $temp->save();
                $temporary_commands->status = "2";
                $temporary_commands->save();
                return Response::json($user);  
            }
        }
    }

    public function deleteUser(Request $request){
        $temporary_command = TemporaryTeam::where('command_id', $request->command_id)->where('user_id', $request->user_id)->first();
        if(!empty($temporary_command)){
            $temporary_command->delete();
            $temporary_commands = CommandsApply::where('command_id', $request->command_id)->where('user_id', $request->user_id)->first();
            $temporary_commands->status = "3";
            $temporary_commands->save();
        }
        return Response::json("Okey");
    }
    public function cancelUser(Request $request){
        $temporary_applys = CommandsApply::where('command_id', $request->command_id)->where('user_id', $request->user_id)->get();
        if(count($temporary_applys) != 0){
            foreach ($temporary_applys as $temporary_apply) {
                $temporary_apply->status = "1";
                $temporary_apply->save();
            }
        }
        return Response::json("Okey");
    }

    public function getCommand(){
        $commands = Commands::where('region',config('EnvSettings.REGION'))->get();
        if(count($commands) == 0){
            return Response::json("None");
        }
       return Response::json($commands); 
    }

    public function checkCommand(Request $request){
        $commands = Commands::where('region',config('EnvSettings.REGION'))->where('command_name', $request->command_name)->first();
        $temporary_applys = CommandsApply::where('command_id', $commands->id)->where('user_id', Auth::user()->id)->where('status', '0')->first();
        $temporary_applys_present = CommandsApply::where('command_id', $commands->id)->where('user_id', Auth::user()->id)->where('status', '2')->first();
        if(!empty($commands)){
            if(!empty($temporary_applys)){
                return Response::json("Exist"); 
            }
            if(!empty($temporary_applys_present)){
                return Response::json("Present"); 
            }
            $command_name = $commands->command_name;
            $CA = new CommandsApply;
            $CA->command_id = $commands->id;
            $CA->user_id = Auth::user()->id;
            $CA->status = "0";
            $CA->save();
            return Response::json($CA); 
        }else{
            return Response::json("Error"); 
        }        
    }

    public function ExitCommand(Request $request){
        $temporary_applys = CommandsApply::where('command_id', $request->command_id)->where('user_id', Auth::user()->id)->where('status', '2')->first();
        
        $temporary_applys->status = "4";
        $temporary_applys->save();

        $temporary_command = TemporaryTeam::where('command_id', $request->command_id)->where('user_id', Auth::user()->id)->first();
        $temporary_command->delete();
        return Response::json("okey"); 
    }



    // public function myCommandApplyers($game_id, $command_id){
    //     $buffer_players = GameCommandContents::where('command_id', '=', $command_id)->where('game_id','=',$game_id)->get();
    //     $apply_users = [];
    //     if(count($buffer_players) != 0){
    //         foreach($buffer_players as $buffer_player){
    //             $buffer_player = $buffer_player->buffer_players;
    //                 if($buffer_player != "None"){
    //                 $bps = explode(' | ',$buffer_player);
    //                 foreach ($bps as $bp) {
    //                     $bp_id = explode('=',$bp); $bp_id=$bp_id[1];
    //                     $user = User::find($bp_id);
    //                     if(!empty($user)){
    //                         array_push($apply_users,$user);
    //                     }
                        
    //                 }
    //             }
    //         }
    //     }
    //     if(empty($apply_users)){
    //         $apply_users = "None";
    //     }
    //     $users = [];
    //     if(count($buffer_players) != 0){
    //         foreach($buffer_players as $players){
    //             $players = $players->players;
    //                 if($players != "None"){
    //                 $bps = explode(' | ',$players);
    //                 foreach ($bps as $bp) {
    //                     $bp_id = explode('=',$bp); $bp_id=$bp_id[1];
    //                     $user = User::find($bp_id);
    //                     if(!empty($user)){
    //                         array_push($users,$user);
    //                     }
                        
    //                 }
    //             }
    //         }
    //     }
        
    //     if(empty($users)){
    //         $users = "None";
    //     }
    //     return Response::json(['apply_users'=>$apply_users, 'users'=>$users]);
    // }

    // public function myCommandApplyUser(Request $request, $id_user){
    //     $global_user = User::find($id_user);
    //     $buffer_players = GameCommandContents::where('command_id', '=', $request->command_id)->where('game_id','=',$request->game_id)->get();
    //     if(count($buffer_players) != 0){
    //         foreach($buffer_players as $buffer_player){
    //             $count = 0;
    //             $bf_pl =  $buffer_player->buffer_players;
    //                 if($bf_pl != "None"){
    //                     $bps = explode(' | ',$bf_pl);
    //                     foreach ($bps as $bp) {
    //                         $bp_id = explode('=',$bp); $bp_id=$bp_id[1];
    //                         if($bp_id != $id_user){
    //                             $user = User::find($bp_id);
    //                             if(!empty($user)){
    //                                 if($count == 0){
    //                                     $buffer_player->buffer_players = "" . $user->login . "=" . $user->id; 
    //                                 }else{
    //                                     $buffer_player->buffer_players .= " | " . $user->login . "=" . $user->id;
    //                                 }
    //                             }
    //                         }else{
    //                             $user = User::find($bp_id);
    //                             if(!empty($user)){
    //                                 $buffer_player->players .= " | " . $user->login . "=" . $user->id;

    //                                 $temporary = new TemporaryTeam;
    //                                 $temporary->user_id = $user->id;
    //                                 $temporary->game_id = $request->game_id;
    //                                 $temporary->command_id = $request->command_id;
    //                                 $temporary->region = env('REGION');

    //                                 $temporary->save();
    //                             }
    //                         } 
    //                     }
    //                 }
    //                 if($count == 0){
    //                     $buffer_player->buffer_players="None";
    //                 }
    //             $buffer_player->save();
    //         }
    //     }

    //     $buffer_players = GameCommandContents::where('game_id','=', $request->game_id)->where('buffer_players','!=',"None")->get();
    //     if(count($buffer_players) != 0){

    //         foreach($buffer_players as $buffer_player){

    //             $count = 0;
    //             $bf_pl =  $buffer_player->buffer_players;
    //                 if($bf_pl != "None"){
    //                     $bps = explode(' | ',$bf_pl);
    //                     foreach ($bps as $bp) {
    //                         $bp_id = explode('=',$bp); $bp_id=$bp_id[1];
    //                         if($bp_id != $id_user){
    //                             $user = User::find($bp_id);
    //                             if(!empty($user)){
    //                                 if($count == 0){
    //                                     $buffer_player->buffer_players = "" . $user->login . "=" . $user->id; 
    //                                 }else{
    //                                     $buffer_player->buffer_players .= " | " . $user->login . "=" . $user->id;
    //                                 }
    //                             }
    //                         }
    //                 }
    //                 if($count == 0){
    //                     $buffer_player->buffer_players="None";
    //                 }
    //                 $buffer_player->save();
    //                 }
    //         }
    //     }
    //     return Response::json($global_user);
    // }

    // public function myCommandDeleteUser(Request $request, $id_user){
    //     $buffer_players = GameCommandContents::where('command_id', '=', $request->command_id)->where('game_id','=',$request->game_id)->get();
    //     if(count($buffer_players) != 0){
    //         foreach($buffer_players as $buffer_player){
    //             $count = 0;
    //             $bf_pl =  $buffer_player->players;
    //                 if($bf_pl != "None"){
    //                     $bps = explode(' | ',$bf_pl);
    //                     foreach ($bps as $bp) {
    //                         $bp_id = explode('=',$bp); $bp_id=$bp_id[1];
    //                         if($bp_id != $id_user){
    //                             $user = User::find($bp_id);
    //                             if(!empty($user)){
    //                                 if($count == 0){
    //                                     $buffer_player->players = "" . $user->login . "=" . $user->id; 
    //                                 }else{
    //                                     $buffer_player->players .= " | " . $user->login . "=" . $user->id;
    //                                 }
    //                               $count++;  
    //                             }
    //                         }else{
    //                             $user = User::find($bp_id);
    //                             if(!empty($user)){
    //                                 $temporary = TemporaryTeam::where('game_id', '=', $request->game_id)->where('command_id', '=', $request->command_id)->where('user_id', '=', $user->id)->first();

    //                                 if(!empty($temporary)){
    //                                     $temporary->delete();;
    //                                 }
    //                             }
    //                         } 
    //                     }
    //                 }

    //                 if($count == 0 || $bf_pl == "None"){
    //                     $buffer_player->players="None";
    //                 }

    //             $buffer_player->save();
    //         }
    //     }
    //     return Response::json(123);
    // }

}
