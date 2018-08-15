<?php

namespace App\Http\Controllers\Admin;
use Config;
use App\Rating;
use App\User;
use App\Commands;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GlobalRating extends Controller
{
    public function load(){
    	// $users = DB::table('Table 17')->get();
    	// foreach ($users as $user) {
    	// 	$last_user = User::orderBy('created_at', 'desc')->first()->id;
    	// 	$new_user = new User;
    	// 	if($user->user_city == "kiev"){
    	// 		$new_user->region = "Kiev";
    	// 	}else{
    	// 		$new_user->region = $user->user_city;
    	// 	}
    	// 	$empty = User::where('login', $user->user_login)->first();
    	// 	if(!empty($user->user_login) && empty($empty)){
    	// 		$new_user->login = $user->user_login;
    	// 	}else{
    	// 		continue;
    	// 	}

    	// 	if(!empty($user->user_name)){
    	// 		$new_user->name = $user->user_name;
    	// 	}else{
    	// 		continue;
    	// 	}
    	// 	$empty = User::where('email', $user->user_email)->first();
    	// 	if(!empty($user->user_email) && empty($empty)){
    	// 		$new_user->email = $user->user_email;
    	// 	}else{
    	// 		continue;
    	// 	}
    	// 	$empty = User::where('tel', $user->user_phone)->first();
    	// 	if($user->user_phone == " dmitriy_litvinov@yahoo.com"){
    	// 		continue;
    	// 	}
    	// 	if(!empty($user->user_phone) && empty($empty)){
    	// 		$new_user->tel = $user->user_phone;
    	// 	}else{
    	// 		continue;
    	// 	}


    	// 	$new_user->password = $user->user_password;
    	// 	$new_user->verified = 1;
    	// 	$new_user->is_admin = "user";
    	// 	$new_user->user_img_path = "None";
    	// 	$new_user->user_img_name = "None";
    	// 	$new_user->save();
    	// }
    
    	$users = Commands::all();
    	$id = 1;
    	foreach($users as $user){
    		$user->id = $id;$id++;
    		$user->save();
    	}


    	// $commands = DB::table('Table 18')->get();
    	// foreach ($commands as $commandas) {
    	// 	$user = User::where('login', $commandas->capitan)->first();
    	// 	if(!empty($user) && !empty($commandas->name)){
    	// 		$dd_command = Commands::where('command_name', $commandas->name)->first();
    	// 		if(empty($dd_command)){
    	// 			$command = new Commands;
	    // 			$command->id_user = $user->id;
	    // 			$command->count = 1;
	    // 			$command->command_name = $commandas->name;
	    // 			$command->command_img_path = "None";
	    // 			$command->command_img_name = "None";
	    // 			$command->region = $user->region;
	    // 			$command->save();
    	// 		}
    			
    	// 	}
    	// }

    }
}
