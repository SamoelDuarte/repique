<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin/dashboard/index');
    }

    public function login()
    {
        return view('admin/login/index');
    }

    public function attempt(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email|max:255',
            'password' => 'bail|required'
        ]);
      
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'E-mail Não Consta no Sistema.'])->withInput();
        }
    
        if (!Utils::passwordIsValid($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Senha Inválida'])->withInput();
        }

        if ($user->role == "user") {
            return back();
        }

        session([
            'authenticated' => true,
            'userData' => $user
        ]);

        return redirect('/admin/calculo');
    }
}
