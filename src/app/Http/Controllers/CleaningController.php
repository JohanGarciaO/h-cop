<?php

namespace App\Http\Controllers;

use App\Models\Cleaning;
use Illuminate\Http\Request;
use App\Enums\RoomCleaningStatus;

class CleaningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cleaning $cleaning)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cleaning $cleaning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cleaning $cleaning)
    {
        if (!auth()->user()->can('clear', $cleaning->room)) {
            return redirect()->route('rooms.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "Você não pode alterar o status deste quarto.",
            ]);
        }

        // Validação
        $validated = $request->validate([
            'housekeeper_id' => 'required|exists:housekeepers,id',
            'status' => ['required', 'in:' . implode(',', RoomCleaningStatus::getNames())],
            'notes' => ['nullable', 'string', 'max:1000'],
        ],[
            'housekeeper_id.required' => 'É obrigatório dizer quem passou as alterações.',
            'housekeeper_id.exists' => 'Este camareiro(a) não existe.',
            'status.required' => 'O estado do quarto é obrigatório.',
            'status.in' => 'Este estado de quarto é inválido.',
            'notes.string' => 'As notas de alterações devem ter um formato textual.',
        ]);

        if ($validated['status'] === 'READY'){
            $validated['notes'] = null;
        }

        // Atualiza a limpeza
        $cleaning->update([
            'housekeeper_id' => $validated['housekeeper_id'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "O estado do quarto <b>".$cleaning->room->number."</b> foi alterado com sucesso.",
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cleaning $cleaning)
    {
        //
    }
}
