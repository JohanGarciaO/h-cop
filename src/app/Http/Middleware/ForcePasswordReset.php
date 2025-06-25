<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordReset
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $defaultPassword = config('auth.default_password', 'hcop*123');

        if (Hash::check($defaultPassword, $user->password)) 
        {   
            // Redireciona para a view de redefinição (caso ainda não esteja nela)
            if (!$request->is('login/password-update'))
            {
                return redirect()->route('auth.form.update-password')->with([
                    'alert-type' => 'warning',
                    'message' => 'Você precisa redefinir sua senha antes de usar o sistema.',
                ]);
            }
        }

        return $next($request);
    }
}
