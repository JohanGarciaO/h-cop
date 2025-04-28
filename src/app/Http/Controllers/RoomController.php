<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('activeReservations')->get();    
        return view('site.rooms', compact('rooms'));
    }
    
    public function store(Request $request)
    {
        $request->valide([
            'number' => 'required|integer|unique:rooms,number',
            'capacity' => 'integer|min:1',
        ],
        [
            'number.required' => 'O número não pode estar vazio.',
            'number.integer' => 'O número do quarto deve ser um número inteiro.',
            'number.unique' => 'Já existe um quarto com o número digitado.',
            'capacity.integer' => 'A capacidade do quarto deve ser um número inteiro.',
            'capacity.min' => 'A capacidade mínima do quarto é 1.',
        ]);

        Room::create($request);

        return redirect()->route('site.rooms')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'Quarto criado com sucesso.',
        ]);
    }
    
    public function update(Request $request, string $id)
    {
        $request->valide([
            'number' => 'required|integer|unique:rooms,number',
            'capacity' => 'integer|min:1',
        ],
        [
            'number.required' => 'O número não pode estar vazio.',
            'number.integer' => 'O número do quarto deve ser um número inteiro.',
            'number.unique' => 'Já existe um quarto com o número digitado.',
            'capacity.integer' => 'A capacidade do quarto deve ser um número inteiro.',
            'capacity.min' => 'A capacidade mínima do quarto é 1.',
        ]);

        Room::update($request->only(['number', 'capacity']));

        return redirect()->route('site.rooms')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'Quarto alterado com sucesso.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('site.rooms')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'Quarto removido com sucesso.',
        ]);
    }
}
