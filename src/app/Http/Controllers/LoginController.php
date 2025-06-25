<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            ])->withInput();
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ],[
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
        ]);

        if ($request->password === config('auth.default_password'))
        {
            return redirect()->route('auth.form.update-password')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Você não pode usar a senha padrão!',
            ]);
        }

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('home.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'Senha atualizada com sucesso!',
        ]);
    }
}
