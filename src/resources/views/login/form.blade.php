@extends('layouts.login')
@section('title', 'Login')

@section('content')
    
<div class="container d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">
    
    <div class="text-center mb-4">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 150px;">
    </div>

    @if($message = Session::get('message'))
        <div class="toast-container bottom-0 end-0 p-3">
            <x-toast type="{{Session::get('alert-type')}}" :message="$message"/>
        </div>
    @endif

    {{-- Mostra Erros de validação do Backend --}}
    @if ($errors->any())
        <div class="toast-container bottom-0 end-0 p-3">
            @foreach ($errors->all() as $error)
                <x-toast type="danger" :message="$error" />            
            @endforeach
        </div>
    @endif

    <div class="card border-0" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4 text-white">
            <form method="POST" action="{{ route('auth.login') }}">
                @csrf

                <div class="mb-3">
                    <label for="login" class="form-label">E-mail ou usuário</label>
                    <input type="text" class="form-control" id="login" name="login" placeholder="Digite seu e-mail ou usuário" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>

                <button type="submit" id="btn-login" class="btn btn-info w-100">Entrar</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    @include('partials.components.js.toast')
@endpush