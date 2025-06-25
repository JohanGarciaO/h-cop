@extends('layouts.home')
@section('title', 'Usuários')

@section('content')
    
    @component('partials.components.body-header', ['title' => 'Gerenciamento de Usuários'])
        @slot('buttons')
            <div>
                <button class="btn btn-core" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 20 20">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                    </svg> 
                    Novo usuário
                </button>
            </div>
            <!-- Modal de Criação de Novo Usuário -->
            @include('partials.modals.users.create')
        @endslot
    @endcomponent

    <x-filters action="{{ route('users.index') }}" results_count="{{ $result_count }}" >
        <x-slot name="filters">
            <x-slot name="filters_title">Filtros simples</x-slot>
            
            <div class="col-auto">
                <input type="text" name="username_filter" class="form-control" placeholder="Nome do usuário" value="{{ request('username_filter') }}">
            </div>

            <div class="col-auto">
                <input type="text" name="name_filter" class="form-control" placeholder="Nome da pessoa" value="{{ request('name_filter') }}">
            </div>

        </x-slot>

    </x-filters>

    <div class="table-responsive">
        <table class="table table-dark table-hover table-striped align-middle shadow-sm">
            <thead>
                <tr>
                    <th>Função</th>
                    <th>Usuário</th> 
                    <th>Nome</th>
                    <th>Documento</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>

                        <td>
                            @if ($user->role_label === 'administrador')
                                <span class="badge bg-danger">{{ $user->role_label }}</span>
                            @else
                                <span class="badge bg-primary">{{ $user->role_label }}</span>
                            @endif                            
                        </td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->document }}</td>
                        <td>{{ $user->phone ?? '-'}}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            <div class="btn-group" role="group">

                                {{-- <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-primary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-vcard-fill" viewBox="0 0 18 18">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0"/>
                                    </svg> Ver
                                </a> --}}

                                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg> Editar
                                </button>

                                <button id="resetUserButton{{ $user->id }}" type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#resetUserModal{{ $user->id }}">
                                    <span class="btn-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 18 18">
                                            <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                        </svg> Resetar senha
                                    </span>
                                    <span class="spinner-content d-none">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Resetando...
                                    </span>
                                </button>
                                  
                                <button id="deleteUserButton{{$user->id}}" type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                    <span class="btn-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="-2 -2 20 20">
                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                        </svg>
                                    </span>
                                    <span class="spinner-content d-none">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </span>
                                </button>

                            </div>
                        </td>

                    </tr>

                    <!-- Modal de Edição de Usuários -->
                    @include('partials.modals.users.edit', ['user' => $user])
                    <!-- Modal de Reset de Senha -->
                    @include('partials.modals.users.reset', ['user' => $user])
                    <!-- Modal de Remoção de Usuários -->
                    @include('partials.modals.users.delete', ['user' => $user])
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('partials.pagination') }}
    </div>

@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        //
    })

</script>
@endpush
