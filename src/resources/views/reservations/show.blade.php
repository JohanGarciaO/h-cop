@extends('layouts.home')
@section('title', 'Gerenciar reserva')

@section('content')

    @component('partials.components.body-header', ['title' => 'Gerenciar Reserva'])
        @slot('buttons')
            <div>
                <a class="btn btn-outline-secondary" href="javascript:history.back()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 20 20">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg>
                    Voltar
                </a>
                @if (!$reservation->check_out_at)
                    @can('update', $reservation)
                        <button id="btn_submit_form" class="btn btn-outline-core" data-bs-toggle="modal" data-bs-target="#editReservationModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                            Editar                       
                        </button>
                        <!-- Modal de Edição da Reserva -->
                        @include('partials.modals.reservations.edit', ['reservation' => $reservation])
                    @endcan
                @else
                    <button id="btn_download" class="btn btn-outline-core" data-url="{{ route('reservations.receipt.download', $reservation->id) }}">
                        <span class="btn-content">
                            <i class="bi bi-download"></i> Baixar Recibo
                        </span>
                        <span class="spinner-content d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span> Baixando...</span>
                        </span>
                    </button>
                @endif
            </div>
        @endslot
    @endcomponent

    <div class="container my-5">
        <div class="row justify-content-md-center">
            
            <div class="col-12 col-lg-5 p-4 bg-background shadow rounded">
                
                <h5 class="mb-5 fw-bold">Reserva N° #{{$reservation->id}}</h5>

                <dl class="row">
                    <dt class="col-sm-6">Status:</dt>
                    <dd class="col-sm-6">
                        {{$reservation->status}}
                    </dd>

                    <dt class="col-sm-6">Quarto:</dt>
                    <dd class="col-sm-6">{{ $reservation->room->number }}</dd>

                    <dt class="col-sm-6">Hóspede:</dt>
                    <dd class="col-sm-6">{{ $reservation->guest->name }}</dd>

                    <dt class="col-sm-6">Gênero:</dt>
                    <dd class="col-sm-6">{{ $reservation->guest->gender->label() }}</dd>

                    @if ($reservation->guest->email)
                        <dt class="col-sm-6">E-mail:</dt>
                        <dd class="col-sm-6">{{ $reservation->guest->email }}</dd>
                    @endif

                    <dt class="col-sm-6">Telefone:</dt>
                    <dd class="col-sm-6">{{ $reservation->guest->phone }}</dd>

                    @if ($reservation->guest?->committee)
                        <dt class="col-sm-6">Comitiva:</dt>
                        <dd class="col-sm-6">{{ $reservation->guest->committee->name ?? '-' }}</dd>
                    @endif

                    <dt class="col-sm-6">Valor da Diária:</dt>
                    <dd class="col-sm-6">R$ {{ number_format($reservation->daily_price, 2, ',', '.') }}</dd>

                    <dt class="col-sm-6">Entrada agendada:</dt>
                    <dd class="col-sm-6">{{ \Carbon\Carbon::parse($reservation->scheduled_check_in)->format('d/m/Y') }}</dd>

                    <dt class="col-sm-6">Saída agendada:</dt>
                    <dd class="col-sm-6">{{ \Carbon\Carbon::parse($reservation->scheduled_check_out)->format('d/m/Y') }}</dd>

                    <dt class="col-sm-6">Check-in:</dt>
                    <dd class="col-sm-6">
                        {{ $reservation->check_in_at ? \Carbon\Carbon::parse($reservation->check_in_at)->format('d/m/Y à\s H:i') : 'Não realizado' }}
                    </dd>

                    <dt class="col-sm-6">Check-out:</dt>
                    <dd class="col-sm-6">
                        {{ $reservation->check_out_at ? \Carbon\Carbon::parse($reservation->check_out_at)->format('d/m/Y à\s H:i') : 'Não realizado' }}
                    </dd>

                    <div class="row g-3 mt-3">
                        <div class="col-md-12">
                            <h5 for="total_display" class="form-label">Resumo da reserva:</h5>
                            <div class="form-control bg-light d-flex align-items-center justify-content-between" style="transition: all 0.3s ease; font-weight: 500;">

                                @if (!$reservation->check_in_at)
                                {{-- Se não tiver sido feito o check-in retorna a diferença de dias do agendado --}}
                                    <div>
                                        <i class="bi bi-calendar-week me-2 text-primary"></i>
                                        <span class="">{{$reservation->numberOfDays . ' ' . Str::plural('diária', $reservation->numberOfDays) . ' ' . Str::plural('agendada', $reservation->numberOfDays)}}</span>
                                    </div>
                                    <div>
                                        <i class="bi bi-currency-dollar me-1 text-success"></i>
                                        <span class="">R$ {{number_format($reservation->totalPrice, 2, ',', '.')}}</span>
                                    </div>
                                @elseif($reservation->check_in_at && !$reservation->check_out_at)
                                {{-- Se tiver sido feito o Check-in mas não o Check-out retorna a diferença de dias do check-in até o momento atual --}}
                                    <div>
                                        <i class="bi bi-calendar-week me-2 text-primary"></i>
                                        <span class="">{{$reservation->numberOfDays . ' ' . Str::plural('diária', $reservation->numberOfDays) . ' até agora'}}</span>
                                    </div>
                                    <div>
                                        <i class="bi bi-currency-dollar me-1 text-success"></i>
                                        <span class="">R$ {{number_format($reservation->totalPrice, 2, ',', '.')}}</span>
                                    </div>
                                @elseif ($reservation->check_in_at && $reservation->check_out_at)
                                {{-- Mas se tiver sido feito tanto o check-in quanto o check-out, retorna a diferença de dias do checkIn até o checkOut --}}
                                    <div>
                                        <i class="bi bi-calendar-week me-2 text-primary"></i>
                                        <span class="text-success">{{$reservation->numberOfDaysScheduled . ' ' . Str::plural('diária', $reservation->numberOfDaysScheduled) . ' ' . Str::plural('agendada', $reservation->numberOfDaysScheduled)}}</span>
                                    </div>
                                    <div>
                                        <i class="bi bi-currency-dollar me-1 text-success"></i>
                                        <span class="text-success">R$ {{number_format($reservation->totalPriceScheduled, 2, ',', '.')}}</span>
                                    </div>
                                @endif

                            </div>
                        </div>    

                        @if(!$reservation->check_in_at)
                            @if ($reservation->scheduled_check_in->isFuture())
                                <div class="col-md-12 mt-3">
                                    <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill gap-2"></i>
                                        <div>Ainda não chegou a data agendada para o Check-in</div>
                                    </div>
                                </div>
                            @endif

                            @if ($reservation->scheduled_check_in->isBefore(now()->startOfDay()))
                                <div class="col-md-12 mt-3">
                                    <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill gap-2"></i>
                                        <div>Já passou a data agendada para o Check-in</div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if($reservation->check_out_at)
                            <div class="col-md-12">
                                <div 
                                    class="form-control bg-light d-flex align-items-center justify-content-between"
                                    style="transition: all 0.3s ease; font-weight: 500;"
                                >
                                    <div>
                                        <i class="bi bi-calendar-week me-2 text-primary"></i>
                                        <span class="text-danger">{{($reservation->numberOfDaysLate) . ' ' . Str::plural('diária', ($reservation->numberOfDaysLate))}} a mais</span>
                                    </div>
                                    <div>
                                        <i class="bi bi-currency-dollar me-1 text-danger"></i>
                                        <span class="text-danger">R$ {{number_format($reservation->totalPriceLate, 2, ',', '.')}}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <h5 for="total_display" class="form-label">Total:</h5>
                                <div 
                                    class="form-control bg-light d-flex align-items-center justify-content-between"
                                    style="transition: all 0.3s ease; font-weight: 500;"
                                >
                                    <div>
                                        <i class="bi bi-calendar-week me-2 text-primary"></i>
                                        <span>{{$reservation->numberOfDays . ' ' . Str::plural('diária', $reservation->numberOfDays)}}</span>
                                    </div>
                                    <div>
                                        <i class="bi bi-currency-dollar me-1"></i>
                                        <span>R$ {{number_format(($reservation->totalPrice), 2, ',', '.')}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                </dl>

                <div class="d-flex gap-2">
                    @if (!$reservation->scheduled_check_in->isFuture() && !$reservation->scheduled_check_in->isBefore(now()->startOfDay()))
                        @if (!$reservation->check_in_at)
                            <button id="checkInButton" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkInModal">
                                <span class="btn-content">
                                    <i class="bi bi-door-open"></i> Fazer Check-in
                                </span>
                                <span class="spinner-content d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span> Fazendo Check-in...</span>
                                </span>
                            </button>
                        @endif
                    @endif

                    @if ($reservation->check_in_at && !$reservation->check_out_at)
                        <button id="checkOutButton" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#checkOutModal">
                            <span class="btn-content">
                                <i class="bi bi-door-closed"></i> Fazer Check-out
                            </span>
                            <span class="spinner-content d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span> Fazendo Check-out...</span>
                            </span>
                        </button>
                    @endif
                </div>

            </div>

            <div class="col-8 col-lg-7 d-none d-lg-flex justify-content-center align-items-center">
                <img src="{{asset('assets/images/edit.webp')}}" alt="Ilustração de edição" class="img-fluid" style="max-height: 800px;">
            </div>

        </div>
    </div>

@include('partials.modals.reservations.check-in', ['reservation' => $reservation])
@include('partials.modals.reservations.check-out', ['reservation' => $reservation])
@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        
        const urlParams = new URLSearchParams(window.location.search);
        const openEdit = urlParams.get('edit');

        if (openEdit === '1') {
            const modal = new bootstrap.Modal(document.getElementById('editReservationModal'));
            modal.show();
        }

        $('#btn_download').on('click', function (e) {
            e.preventDefault();

            const button = $(this);
            const url = button.data('url');

            // Mostrar loading
            button.find('.btn-content').addClass('d-none');
            button.find('.spinner-content').removeClass('d-none');
            button.prop('disabled', true);

            $.ajax({
                url: url,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (data, status, xhr) {
                    const blob = new Blob([data], { type: xhr.getResponseHeader('Content-Type') });
                    const downloadUrl = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');

                    const contentDisposition = xhr.getResponseHeader('Content-Disposition');
                    let filename = 'recibo.pdf';

                    if (contentDisposition && contentDisposition.indexOf('filename=') !== -1) {
                        filename = contentDisposition.split('filename=')[1].replaceAll('"', '').trim();
                    }

                    a.href = downloadUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(downloadUrl);
                },
                error: function (xhr) {
                    let message = 'Erro ao baixar o recibo.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showToast(message, 'danger');
                },
                complete: function () {
                    // Restaurar botão
                    button.find('.btn-content').removeClass('d-none');
                    button.find('.spinner-content').addClass('d-none');
                    button.prop('disabled', false);
                }
            });
        })

    })

</script>
@include('reservations.select2', ['modalId' => 'editReservationModal'])
@endpush