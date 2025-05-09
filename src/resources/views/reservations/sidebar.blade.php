<!-- Sidebar -->
<div class="sidebar" id="sidebar">

    <h5 class="sidebar-title">Menu</h5>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('home.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 20 20">
                    <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
                </svg>
                <span class="hide">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('rooms.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" viewBox="4 3 21 21"><title>bed-double-outline</title><path d="M8 5C7.5 5 7 5.21 6.61 5.6S6 6.45 6 7V10C5.47 10 5 10.19 4.59 10.59S4 11.47 4 12V17H5.34L6 19H7L7.69 17H16.36L17 19H18L18.66 17H20V12C20 11.47 19.81 11 19.41 10.59S18.53 10 18 10V7C18 6.45 17.8 6 17.39 5.6S16.5 5 16 5M8 7H11V10H8M13 7H16V10H13M6 12H18V15H6Z" /></svg>
                <span class="hide">Quartos</span>
            </a>
        </li>
        <li class="nav-item">                    
            <a class="nav-link text-white" href="{{ route('guests.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 20 20">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
                <span class="hide">Hóspedes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('reservations.index') }}">">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar-check-fill" viewBox="0 0 20 20">
                    <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-5.146-5.146-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                </svg>
                <span class="hide">Reservas</span>
            </a>
        </li>
    </ul>
</div>

@push('scripts')
<script>

    document.getElementById('toggleSidebarBtn').addEventListener('click', () => {
        const sidebar = document.getElementById('sidebar')
        sidebar.classList.toggle('collapsed')

        const button = document.getElementById('toggleSidebarBtn')
        button.style.transform ? button.style.transform = '' : button.style.transform = 'rotate(180deg)'
    })
    
</script>
@endpush