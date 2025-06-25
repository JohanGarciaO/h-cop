@extends('layouts.login')
@section('title', 'Redefinir Senha')

@section('content')
    
<div class="container d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">
    
    <div class="text-center mb-4">
        <img src="{{asset("assets/images/".config('app.logo').".png")}}" alt="Logo" class="img-fluid" style="max-width: 150px;">
    </div>

    <div class="card border-0" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4 text-white bg-background">
            <form id="updateForm" method="POST" action="{{ route('auth.update.password') }}">
                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" minlength="8" placeholder="Digite sua senha" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Repita a senha</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="8" placeholder="Digite a senha novamente" required>
                </div>

                <button id="btnLogin" type="submit" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2 w-100">
                    <span class="btn-content">
                        <span>Redefinir</span>
                    </span>
                    <span class="spinner-content d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Mudando sua senha...</span>
                    </span>
                </button>

                @if (!Hash::check(config('auth.default_password'), auth()->user()->password))
                    <a id="home" class="btn btn-outline-primary d-flex align-items-center justify-content-center mt-3 w-100">
                        <i class="bi bi-house-door-fill"></i>
                        <span>&nbsp;Voltar para o início</span>
                    </a>
                @endif


            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>

    // Loading button
    $('#updateForm').on('submit', function () {
        const $btn = $('#btnLogin');
        // Desativa botão
        $btn.prop('disabled', true);
        // Alterna visibilidade dos elementos
        $btn.find('.btn-content').addClass('d-none');
        $btn.find('.spinner-content').removeClass('d-none');
    });

    $('#home').click(function (e) {
        e.preventDefault()

        if (window.history.length > 1) {
            window.history.back()
        }else{
            window.location.href = "{{ route('home.index') }}"
        }
    });

</script>
@endpush