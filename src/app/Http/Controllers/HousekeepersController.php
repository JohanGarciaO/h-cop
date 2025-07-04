<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Housekeeper;

class HousekeepersController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', auth()->user());

        $housekeepers = Housekeeper::withCount('cleanings');

        // Filtro por nome
        if ($request->filled('name_filter')) {
            $housekeepers->where('name', 'like', '%' . $request->name_filter . '%');
        }

        // Filtro por documento
        if ($request->filled('document_filter')) {
            $housekeepers->where('document', $request->document_filter);
        }

        $housekeepers = $housekeepers->orderBy('name')->paginate(12)->appends($request->query());

        return view('housekeepers.index',[
            'housekeepers' => $housekeepers,
            'result_count' => $housekeepers->total()
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
        $validated = $request->validate([
            'name' => 'required|string',
            'document' => 'required|max:14|unique:users',
            'phone' => 'nullable|string|max:15',
        ],[
            'name.required' => 'O nome completo é obrigatório.',
            'name.string' => 'O nome deve ter um formato textual.',
            'document.required' => 'O documento é obrigatório.',
            'document.max' => 'O tamanho máximo do documento é 11 dígitos numéricos.',
            'document.unique' => 'Este documento já está vinculado a outro usuário.',
            'phone.max' => 'O telefone só pode ter no máximo 15 números.',
        ]);

        $validated['document'] = Str::padLeft($validated['document'], 9, '0');  
        
        Housekeeper::create($validated);

        return redirect()->route('housekeepers.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'Camareiro(a) criado com sucesso.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Housekeepers $housekeepers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Housekeepers $housekeepers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $housekeeper = Housekeeper::find($id);

        if (!$housekeeper) {
            return redirect()->route('housekeepers.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Este camareiro não existe.',
            ]);
        }

        $validated = $request->validate([
            'name' => 'nullable|string',
            'document' => [
                'nullable',
                'max:14',
                Rule::unique('users')->ignore($housekeeper->id)
            ],
            'phone' => 'nullable|string|max:15',
        ],[
            'name.string' => 'O nome deve ter um formato textual.',
            'document.max' => 'O tamanho máximo do documento é 11 dígitos numéricos.',
            'document.unique' => 'Este documento já está vinculado a outro usuário.',
            'phone.max' => 'O telefone só pode ter no máximo 15 números.',
        ]);

        $validated['document'] = Str::padLeft($validated['document'], 9, '0');   

        $housekeeper->update($validated);

        return redirect()->route('housekeepers.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'Camareiro(a) atualizado com sucesso.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $housekeeper = Housekeeper::find($id);

        if (!$housekeeper) {
            return redirect()->route('housekeepers.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Este camareiro não existe.',
            ]);
        }

        try {
            $housekeeper->delete();
        } catch (\Throwable $th) {
            \Log::error('Erro ao remover camareiro: ' . $th->getMessage());
           return redirect()->route('housekeepers.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Erro ao apagar camareiro: ' . $th->getMessage() . '.',
            ]);
        }

        return redirect()->route('housekeepers.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'Camareiro(a) apagado com sucesso.'
        ]);
    }
}
