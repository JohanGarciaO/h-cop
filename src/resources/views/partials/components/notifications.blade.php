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

@push('scripts')
    @include('partials.components.js.toast')
@endpush