<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;
use App\Models\Cleaning;
use App\Services\ReservationReceiptService;
use App\Enums\RoomCleaningStatus;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reservations = Reservation::with(['guest.address', 'room']);

        // Filtros simples 
        if ($request->filled('status')){
            switch ($request->input('status')) {
                case 'completed':
                    $reservations->whereNotNull('check_out_at');
                    break;                
                case 'check-in-pending':
                    $reservations->whereNull('check_in_at');
                    break;
                case 'check-out-pending':
                    $reservations->whereNotNull('check_in_at')->whereNull('check_out_at');
                    break;
            }
        }

        if ($request->filled('number_reservation_filter')){
            $reservations->where('id', $request->input('number_reservation_filter'));
        }

        if ($request->filled('number_room_filter')){
            $reservations->whereHas('room', function ($query) use ($request) {
                $query->where('number', $request->input('number_room_filter'));
            });
        }

        if($request->filled('name_guest_filter')){
            $reservations->whereHas('guest', function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->input('name_guest_filter').'%');
            });
        }

        if($request->filled('cpf_guest_filter')){
            $reservations->whereHas('guest', function ($query) use ($request) {
                $query->where('document', $request->input('cpf_guest_filter'));
            });
        }

        // Filtro por gênero
        if ($request->filled('gender_filter')) {
            $reservations->whereHas('guest', function ($query) use ($request) {
                $query->where('gender', $request->input('gender_filter'));
            });
        }

        // Filtro por gênero
        if ($request->filled('committee_filter')) {
            $reservations->whereHas('guest', function ($query) use ($request) {
                $query->where('committee_id', $request->input('committee_filter'));
            });
        }

        // Filtros por localidade
        if ($request->filled('state_filter_id')) {
            $reservations->whereHas('guest.address', function ($query) use ($request) {
                $query->where('state_id', $request->input('state_filter_id'));
            });
        }
        if ($request->filled('city_filter_id')) {
            $reservations->whereHas('guest.address', function ($query) use ($request) {
                $query->where('city_id', $request->input('city_filter_id'));
            });
        }

        // Filtros por data de entrada e saída
        if($request->filled('check_in')){
            $reservations->whereDate('scheduled_check_in', $request->input('check_in'));
        }
        if($request->filled('check_out')){
            $reservations->whereDate('scheduled_check_out', $request->input('check_out'));
        }

        $reservations = $reservations->orderByDesc('updated_at')->paginate(12)->appends($request->query());
        return view('reservations.index', [
            'reservations' => $reservations,
            'result_count' => $reservations->total(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'scheduled_check_in' => 'required|date|after_or_equal:today',
            'scheduled_check_out' => 'required|date|after:scheduled_check_in',
            'room_id' => 'required|integer|exists:rooms,id',
            'guest_id' => 'required|integer|exists:guests,id',
            'daily_price' => 'required|numeric|min:1',
        ],
        [
            'scheduled_check_in.required' => 'A data de entrada é obrigatória.',
            'scheduled_check_in.date' => 'A data de entrada deve ser um valor do tipo date.',
            'scheduled_check_in.after_or_equal' => 'A data de entrada deve ser hoje ou uma data futura.',
            'scheduled_check_out.required' => 'A data de saída é obrigatória.',
            'scheduled_check_out.date' => 'A data de saída deve ser um valor do tipo date.',
            'scheduled_check_out.after' => 'A data de saída deve ser posterior à de entrada.',
            'room_id.required' => 'O quarto não pode estar vazio.',
            'room_id.integer' => 'O id do quarto deve ser um número inteiro.',
            'room_id.exists' => 'O quarto selecionado é inválido.',
            'guest_id.required' => 'O hóspede não pode estar vazio.',
            'guest_id.integer' => 'O id do hóspede deve ser um número inteiro.',
            'guest_id.exists' => 'O hóspede selecionado é inválido.',
            'daily_price.required' => 'O valor da diária não pode estar vazio.',
            'daily_price.numeric' => 'O valor da diária deve ser um número.',
            'daily_price.min' => 'O valor da diária deve ser no mínimo 1.',
        ]);

        // Verificar conflitos de datas para o mesmo quarto
        $room = Room::findOrFail($request->room_id);
        $conflict = !$room->isAvailableBetween($request->scheduled_check_in, $request->scheduled_check_out);

        if ($conflict) {
            return redirect()->route('reservations.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Já existe uma reserva nesse intervalo para o quarto selecionado.',
            ]);
        }

        $reservation = Reservation::create($request->all());
        $reservation = $reservation->load(['guest', 'room']);
        $reservation->created_by = auth()->id();
        $reservation->save();

        return redirect()->route('reservations.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "A reseva de <b>".$reservation->guest->name."</b> no quarto <b>".$reservation->room->number."</b> foi feita com sucesso.",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservation = Reservation::with(['guest','room'])->findOrFail($id);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->route('reservations.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "Não é possível atualizar uma reserva inexistente.",
            ]);
        }

        // No nível de operador não é mais possível editar após o check-in
        if (!auth()->user()->can('update', $reservation)) {
            return redirect()->route('reservations.update', $id)->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "Você não pode mais alterar esta reserva.",
            ]);
        }

        $request->validate([
            'scheduled_check_in' => 'required|date',
            'scheduled_check_out' => 'required|date|after:scheduled_check_in',
            'room_id' => 'required|integer|exists:rooms,id',
            'guest_id' => 'required|integer|exists:guests,id',
            'daily_price' => 'required|numeric|min:1',
        ],
        [
            'scheduled_check_in.required' => 'A data de entrada é obrigatória.',
            'scheduled_check_in.date' => 'A data de entrada deve ser um valor do tipo date.',
            'scheduled_check_out.required' => 'A data de saída é obrigatória.',
            'scheduled_check_out.date' => 'A data de saída deve ser um valor do tipo date.',
            'scheduled_check_out.after' => 'A data de saída deve ser posterior à de entrada.',
            'room_id.required' => 'O quarto não pode estar vazio.',
            'room_id.integer' => 'O id do quarto deve ser um número inteiro.',
            'room_id.exists' => 'O quarto selecionado é inválido.',
            'guest_id.required' => 'O hóspede não pode estar vazio.',
            'guest_id.integer' => 'O id do hóspede deve ser um número inteiro.',
            'guest_id.exists' => 'O hóspede selecionado é inválido.',
            'daily_price.required' => 'O valor da diária não pode estar vazio.',
            'daily_price.numeric' => 'O valor da diária deve ser um número.',
            'daily_price.min' => 'O valor da diária deve ser no mínimo 1.',
        ]);

        try {
            $reservation->update($request->only(['room_id', 'guest_id', 'daily_price', 'scheduled_check_in', 'scheduled_check_out']));
            $reservation->updated_by = auth()->id();
            $reservation->save();

            return redirect()->route('reservations.update', $id)->with([
                'status' => 'success',
                'alert-type' => 'success',
                'message' => "Reserva atualizada com sucesso.",
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('reservations.update', $id)->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "Ocorreu um erro ao atualizar a reserva.",
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        // No nível de operador não é mais possível apagar reservas após o check-in
        if (!auth()->user()->can('delete', $reservation)) {
            return redirect()->route('reservations.show', $id)->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "Você não pode mais apagar esta reserva.",
            ]);
        }

        $reservation->delete();

        return redirect()->back()->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "A reseva de <b>".$reservation->guest->name."</b> no quarto <b>".$reservation->room->number."</b> foi apagada com sucesso.",
        ]);
    }

    public function checkIn(Reservation $reservation)
    {
        if ($reservation->check_in_at) {
            return redirect()->back()->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "O check-in desta reserva já foi feito.",
            ]);
        }

        if ($reservation->scheduled_check_in->isFuture()){
            return redirect()->back()->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "Ainda não chegou o dia de seu check-in.",
            ]);
        }

        if (Reservation::where('room_id', $reservation->room_id)->whereNull('check_out_at')->count()) {
            return redirect()->back()->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "A última reserva deste quarto ainda está com o check-out pendente.",
            ]);
        }

        DB::transaction(function () use ($reservation) {
            $reservation->check_in_at = Carbon::now();
            $reservation->check_in_by = auth()->id();
            $reservation->save();
        });

        return redirect()->route('reservations.show', $reservation->id)->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "Check-in realizado com sucesso.",
        ]);
    }

    public function checkOut(Reservation $reservation)
    {
        if (!$reservation->check_in_at) {
            return redirect()->back()->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "É necessário fazer o check-in antes do check-out.",
            ]);
        }

        if ($reservation->check_out_at) {
            return redirect()->back()->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "O check-out desta reserva já foi feito.",
            ]);
        }

        DB::transaction(function () use ($reservation) {
            $reservation->check_out_at = Carbon::now();
            $reservation->check_out_by = auth()->id();
            $reservation->save();
        });

        try {
            $receipt = app(ReservationReceiptService::class);
            $path = $receipt->generate($reservation->id);
        } catch (\Throwable $th) {
            \Log::error('Erro ao gerar recibo: ' . $th->getMessage());
            return redirect()->back()->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Erro ao gerar o recibo. Verifique os logs.',
            ]);
        }

        Cleaning::create([
            'room_id' => $reservation->room_id,
            'reservation_id' => $reservation->id,
            'housekeeper_id' => null,
            'status' => RoomCleaningStatus::IN_PREPARATION->name,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('reservations.show', $reservation->id)->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "Check-out realizado com sucesso.",
        ]);
    }

    public function downloadReceipt(string $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->route('reservations.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Reserva não encontrada.',
            ]);
        }

        if ($reservation->isActive()) {
            return redirect()->route('reservations.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'A reserva deve estar finalizada antes de gerar o recibo.',
            ]);
        }

        $disk = Storage::disk('local');

        if (!$reservation->receipt_path || !$disk->exists($reservation->receipt_path)) {
            $path = app(ReservationReceiptService::class)->generate($reservation->id);
            return $disk->download("{$path}", "recibo_reserva_{$reservation->id}.pdf");
        }

        return $disk->download("{$reservation->receipt_path}", "recibo_reserva_{$reservation->id}.pdf");
    }
}
