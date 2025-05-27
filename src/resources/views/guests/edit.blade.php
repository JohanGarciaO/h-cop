@extends('layouts.home')
@section('title', 'Editar hóspede')

@section('content')

    @component('partials.components.body-header', ['title' => 'Gerenciamento de Hóspedes'])
        @slot('buttons')
            <div>
                <a class="btn btn-secondary" href="{{ route('guests.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 20 20">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg>
                    Voltar
                </a>
                <button id="btn_submit_form" class="btn btn-core">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 18 18">
                        <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
                        <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
                    </svg>
                    Salvar
                </button>
            </div>
        @endslot
    @endcomponent

    <div class="container my-5">
        <div class="row justify-content-md-center">
            <form id="form_edit" class="col-12 col-lg-4 p-4 bg-light shadow rounded" action="{{ route('guests.update', $guest) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h5 class="mb-4 text-dark">Formulário de edição</h5>

                <div class="col-12 mb-3">
                    <input type="text" class="form-control" id="name" name="name" minlength="14" placeholder="Digite o nome completo" value="{{ old('name', $guest->name) }}" required>
                </div>

                <div class="col-12 mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite o e-mail" value="{{ old('email', $guest->email) }}">
                </div>

                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="document" name="document" minlength="14" placeholder="Digite o CPF" value="{{ old('document', $guest->document) }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="phone" name="phone" maxlength="15" placeholder="Digite o telefone" value="{{ old('phone', $guest->phone) }}" required>
                    </div>
                   
                <div class="col-12 mb-3">
                    <select class="form-select" id="state_edit_id" name="state_id" data-selected="{{ old('state_id', $guest->address->state_id) }}" data-placeholder="Digite o estado" required>
                        <option></option>
                    </select>
                </div>            
                <div class="col-12 mb-3">
                    <select class="form-select" id="city_edit_id" name="city_id" data-selected="{{ old('city_id', $guest->address->city_id) }}" data-placeholder="Digite a cidade" required>
                        <option></option>
                    </select>
                </div>     
                
                <div class="row mb-3">
                    <div class="col-6">
                        <input type="text" class="form-control" id="postal_code" name="postal_code" maxlength="9" placeholder="Digite o CEP" value="{{ old('postal_code', $guest->address->postal_code) }}"  required>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Digite o bairro" value="{{ old('neighborhood', $guest->address->neighborhood) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-9">
                        <input type="text" class="form-control" id="street" name="street" placeholder="Digite o nome da rua" value="{{ old('street', $guest->address->street) }}"  required>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="number" name="number" placeholder="Número" value="{{ old('number', $guest->address->number) }}">
                    </div>
                </div>                

                <div class="col-12 mb-3">
                    <input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento" value="{{ old('complement', $guest->address->complement) }}">
                </div>

                <button type="submit" id="hidden_submit" class="d-none"></button>

            </form>

            <div class="col-8 d-none d-lg-flex justify-content-center align-items-center">
                <img src="{{asset('assets/images/edit.webp')}}" alt="Ilustração de edição" class="img-fluid" style="max-height: 800px;">
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        $('#document').mask('000.000.000-00')
        $('#postal_code').mask('00000-000')

        $('#phone').on('input', function () {
            let value = this.value.replace(/\D/g,'')            
            value = value.replace(/(\d{2})(\d)/,"($1) $2")
            value = value.replace(/(\d)(\d{4})$/,"$1-$2")            
            this.value = value
        })

        $('#btn_submit_form').click((e) => {
            e.preventDefault()
            $('#hidden_submit').click()
        })
    })

</script>
@include('guests.select2-brasil')
@endpush