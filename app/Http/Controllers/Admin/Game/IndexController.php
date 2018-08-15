<?php

namespace App\Http\Controllers\Admin\Game;

use App\GameBuffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;


class IndexController extends Controller
{
    public function getGameConfirmed($game_id){
    	$game = GameBuffer::find($game_id);
    	return Response::json($game->confirmed);
    }

    public function changeConfirmed($game_id, Request $request){
        $game_buffer = GameBuffer::find($game_id);
        $game_buffer->confirmed = $request->stage;
        $game_buffer->save();
        return Response::json( $game_buffer);
    }
}
