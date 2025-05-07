<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected function credentials(Request $request){
        $login = $request->input('login'); // é o input que o formulário já manda

        // Verifica se é email ou username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    public function auth(Request $request){
        $credenciais = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ],[
            'login.required' => 'O e-mail ou usuário não pode estar vazio.',
            'password.required' => 'A senha não pode estar vazia.',
        ]);

        if(Auth::attempt($this->credentials($request), $request->remember)){
            $request->session()->regenerate();
            return redirect()->intended(route('home.index'));
        }else{
            return redirect()->back()->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Usuário ou senha inválido.'
            ]);
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }
}
