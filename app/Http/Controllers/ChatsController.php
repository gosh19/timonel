<?php

namespace App\Http\Controllers;


use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatsController extends Controller
{
  public function __construct(){
        $this->middleware('auth');
    }

    public function fetchMessages(){
       return Message::with('user')->get();
     }

     public function sendMessage(Request $request){
       $user = Auth::user();

        $message = $user->messages()->create([
             'message' => $request->input('message'),
             'destino_id' => $request->input('destino')
         ]);

        broadcast(new MessageSent($user, $message))->toOthers();

          return ['status' => 'Message Sent!'];
     }
}
