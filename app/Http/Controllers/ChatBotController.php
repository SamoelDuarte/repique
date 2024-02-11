<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatBotController extends Controller
{
  public function index(){
    return view('admin.chatbot.index');
  }
}
