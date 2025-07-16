@extends('layouts.home')
@section('title', 'Comitivas')

@section('content')

    @component('partials.components.body-header', ['title' => 'Gerenciamento de Comitivas'])
        @slot('buttons')
            <div>
                <button class="btn btn-core" data-bs-toggle="modal" data-bs-target="#createCommitteeModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 20 20">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                    </svg>
                    Nova comitiva
                </button>
            </div>
            <!-- Modal de Criação de Nova Comissão -->
            @include('partials.modals.committees.create')
        @endslot
    @endcomponent

    <x-filters action="{{ route('committees.index') }}" results_count="{{ $result_count }}" >
        <x-slot name="filters">
            <x-slot name="filters_title">Filtros simples</x-slot>

            <div class="col-auto">
                <input type="text" name="name_filter" class="form-control" placeholder="Nome da comissão" value="{{ request('name_filter') }}">
            </div>

        </x-slot>

    </x-filters>

    @if (!$committees->isEmpty())
    <div class="table-responsive">
        <table class="table table-dark table-hover table-striped align-middle shadow-sm">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Criação</th>
                    <th>Ultima atualização</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($committees as $committee)
                    <tr>

                        <td>{{ $committee->name }}</td>
                        <td>{{ $committee->description }}</td>
                        <td>criado em <b>{{$committee->created_at_formatted}}</b> por: <b>{{$committee->createdBy->username}}</b></td>
                        <td>atualizado em <b>{{$committee->updated_at_formatted}}</b> por: <b>{{$committee->updatedBy->username}}</b></td>

                        <td>
                            <div class="btn-group" role="group">

                                @can('update', $committee)
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#editCommitteeModal{{ $committee->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg> Editar
                                    </button>
                                @endcan

                                @can('delete', $committee)
                                    <button id="deleteCommitteeButton{{$committee->id}}" type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCommitteeModal{{ $committee->id }}">
                                        <span class="btn-content">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="-2 -2 20 20">
                                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                            </svg>
                                        </span>
                                        <span class="spinner-content d-none">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </span>
                                    </button>
                                @endcan
                            </div>
                        </td>

                    </tr>

                    <!-- Modal de Edição de Comissão -->
                    @include('partials.modals.committees.edit', ['committee' => $committee])
                    <!-- Modal de Remoção de Comissão -->
                    @include('partials.modals.committees.delete', ['committee' => $committee])
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        {{ $committees->links('partials.pagination') }}
    </div>

@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        //
    })

</script>
@endpush
