@extends('layouts.login')
@section('title', 'Login')

@section('content')
    
<div class="container d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">
    
    <div class="text-center mb-4">
        <img src="{{asset("assets/images/".config('app.logo').".png")}}" alt="Logo" class="img-fluid" style="max-width: 150px;">
    </div>

    <div class="card border-0" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4 text-white bg-background">
            <form id="loginForm" method="POST" action="{{ route('auth.auth') }}">
                @csrf

                <div class="mb-3">
                    <label for="login" class="form-label">E-mail ou usuário</label>
                    <input type="text" class="form-control" id="login" name="login" value="{{old('login')}}" placeholder="Digite seu e-mail ou usuário" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>

                <button id="btnLogin" type="submit" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2 w-100">
                    <span class="btn-content">
                        <span>Entrar</span>
                    </span>
                    <span class="spinner-content d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Entrando...</span>
                    </span>
                </button>

                {{-- <button type="submit" id="btn-login" class="btn btn-outline-success w-100">Entrar</button> --}}
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>

    // Loading button
    $('#loginForm').on('submit', function () {
        const $btn = $('#btnLogin');
        // Desativa botão
        $btn.prop('disabled', true);
        // Alterna visibilidade dos elementos
        $btn.find('.btn-content').addClass('d-none');
        $btn.find('.spinner-content').removeClass('d-none');
    });

</script>
@endpush