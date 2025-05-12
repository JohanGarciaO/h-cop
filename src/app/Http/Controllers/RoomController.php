<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{

    public function index(Request $request)
    {
        $rooms = Room::withCount(['activeReservations']);

        // Filtro por número de quarto
        if ($request->filled('room_number')) {
            $rooms->where('number', $request->input('room_number'));
        }

        // Filtro por capacidade mínima
        if ($request->filled('min_capacity')) {
            $rooms->where('capacity', '>=', $request->input('min_capacity'));
        }

        // Filtro por capacidade máxima
        if ($request->filled('max_capacity')) {
            $rooms->where('capacity', '<=', $request->input('max_capacity'));
        }

        // Filtro por vagas livres mínimas
        if ($request->filled('min_free')) {
            $rooms->havingRaw('((capacity) - (active_reservations_count)) >= ?', [(int) $request->input('min_free')]);
        }

        // Filtro por status
        if ($request->filled('status')) {
            switch ($request->input('status')) {
                case 'empty':
                    $rooms->having('active_reservations_count', '=', 0);
                    break;
                case 'crowded':
                    $rooms->havingRaw('capacity <= active_reservations_count');
                    break;
                case 'available':
                    $rooms->havingRaw('capacity > active_reservations_count');
                    break;
                case 'occupied':
                    $rooms->having('active_reservations_count', '>', 0);
                    break;
            }
        }

        // Ordena pelo número e pagina com 12 por página
        $rooms = $rooms->orderBy('number')->paginate(12)->appends(request()->query());

        return view('rooms.index', [
            'rooms' => $rooms,
            'result_count' => $rooms->total(),
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|min:1|unique:rooms,number',
            'capacity' => 'integer|min:1',
            'daily_price' => 'required|numeric|min:1',
        ],
        [
            'number.required' => 'O número não pode estar vazio.',
            'number.integer' => 'O número do quarto deve ser um número inteiro.',
            'number.min' => 'O número do quarto deve ser maior ou igual a 1.',
            'number.unique' => 'Já existe um quarto com o número digitado.',
            'capacity.integer' => 'A capacidade do quarto deve ser um número inteiro.',
            'capacity.min' => 'A capacidade mínima do quarto é 1.',
            'daily_price.required' => 'O valor da diária não pode estar vazio.',
            'daily_price.numeric' => 'O valor da diária deve ser um número.',
            'daily_price.min' => 'O valor da diária deve ser no mínimo 1.',
        ]);

        $room = $request->all();

        Room::create($room);

        return redirect()->route('rooms.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "Quarto <b>número $request->number</b> criado com sucesso.",
        ]);
    }
    
    public function update(Request $request, string $id)
    {
        $request->validate([
            'number' => [
                'required',
                'integer',
                Rule::unique('rooms','number')->ignore($id)
            ],
            'capacity' => 'integer|min:1',
            'daily_price' => 'required|numeric|min:1',
        ],
        [
            'number.required' => 'O número não pode estar vazio.',
            'number.integer' => 'O número do quarto deve ser um número inteiro.',
            'number.unique' => 'Já existe um quarto com o número digitado.',
            'capacity.integer' => 'A capacidade do quarto deve ser um número inteiro.',
            'capacity.min' => 'A capacidade mínima do quarto é 1.',
            'daily_price.required' => 'O valor da diária não pode estar vazio.',
            'daily_price.numeric' => 'O valor da diária deve ser um número.',
            'daily_price.min' => 'O valor da diária deve ser no mínimo 1.',
        ]);

        Room::find($id)->update($request->only(['number', 'capacity', 'daily_price']));

        return redirect()->route('rooms.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "Quarto <b>número $request->number</b> alterado com sucesso.",
        ]);
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "O quarto <b>$room->number</b> foi removido com sucesso.",
        ]);
    }
}
