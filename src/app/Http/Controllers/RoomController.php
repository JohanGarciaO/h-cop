<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{

    public function index()
    {
        $rooms = Room::with('activeReservations')->orderBy('number')->paginate(12);    
        return view('site.rooms', compact('rooms'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|min:1|unique:rooms,number',
            'capacity' => 'integer|min:1',
        ],
        [
            'number.required' => 'O número não pode estar vazio.',
            'number.integer' => 'O número do quarto deve ser um número inteiro.',
            'number.min' => 'O número do quarto deve ser maior ou igual a 1.',
            'number.unique' => 'Já existe um quarto com o número digitado.',
            'capacity.integer' => 'A capacidade do quarto deve ser um número inteiro.',
            'capacity.min' => 'A capacidade mínima do quarto é 1.',
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
        ],
        [
            'number.required' => 'O número não pode estar vazio.',
            'number.integer' => 'O número do quarto deve ser um número inteiro.',
            'number.unique' => 'Já existe um quarto com o número digitado.',
            'capacity.integer' => 'A capacidade do quarto deve ser um número inteiro.',
            'capacity.min' => 'A capacidade mínima do quarto é 1.',
        ]);

        Room::find($id)->update($request->only(['number', 'capacity']));

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
