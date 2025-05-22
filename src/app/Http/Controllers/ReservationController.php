<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reservations = Reservation::with(['guest.address', 'room']);

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('reservations.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "A reseva de <b>".$reservation->guest->name."</b> no quarto <b>".$reservation->room->number."</b> foi apagada com sucesso.",
        ]);
    }
}
