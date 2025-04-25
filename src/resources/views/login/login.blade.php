@extends('layouts.login')
@section('title', 'Login')

@section('content')
    
<div class="container d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">
    
    <div class="text-center mb-4">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 150px;">
    </div>

    <div class="card border-0" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4 text-white">
            <form method="POST" action="{{ route('login.form') }}">
                @csrf

                <!-- Email input -->
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Digite seu e-mail">
                </div>

                <!-- Password input -->
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Digite sua senha">
                </div>

                <!-- Remember me checkbox -->
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>

                <!-- Submit button -->
                <button type="submit" id="btn-login" class="btn btn-info w-100">Entrar</button>
            </form>
        </div>
    </div>
</div>

@endsection