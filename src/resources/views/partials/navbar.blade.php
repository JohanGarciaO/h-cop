<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">

        <button class="btn btn-dark toggle-btn mx-3 ms-0" id="toggleSidebarBtn">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand" href="{{ route('home.index') }}">
            <img src="{{asset("assets/images/".config('app.logo_navbar').".png")}}" alt="Logo" width="30px">
            <span class="d-none d-lg-inline" style="font-size: 12px; letter-spacing: 8px;">{{config('app.logo_navbar_title')}}</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="d-none d-sm-flex">
                    <button type="button" id="fullscreen-toggle" class="btn btn-dark nav-link mx-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                    </button>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <span>Olá, {{ auth()->user()->first_name}}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-dark" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item text-light" href="{{ route('auth.form.update-password') }}">
                                <i class="bi bi-key"></i> Alterar senha
                            </a>
                        </li>

                        <li><hr class="dropdown-divider bg-secondary"></li>

                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('auth.logout') }}">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    document.getElementById('fullscreen-toggle').addEventListener('click', () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.error(`Erro ao ativar fullscreen: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    });
</script>
@endpush
