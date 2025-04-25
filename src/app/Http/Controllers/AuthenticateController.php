<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function auth(Request $request){
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ],[
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail precisa ser válido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        if(Auth::attempt($credenciais, $request->remember)){
            $request->session()->regenerate();
            return redirect()->intended(route('site.index'));
        }else{
            return redirect()->back()->with('mensagem', 'Usuário ou senha inválido.');
        }

    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login.form'));
    }
}
