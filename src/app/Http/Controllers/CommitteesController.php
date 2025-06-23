<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Committee;

class CommitteesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $committees = Committee::with(['createdBy', 'updatedBy'])->withCount('guests');

        // Filtro por nome do hóspede
        if ($request->filled('name_filter')) {
            $committees->where('name', 'like', '%' . $request->name_filter . '%');
        }

        $committees = $committees->orderBy('name')->paginate(12)->appends($request->query());

        return view('committees.index',[
            'committees' => $committees,
            'result_count' => $committees->total()
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
            'name' => [
                'required',
                'string',
                Rule::unique('committees', 'name')
            ],
            'description' => 'nullable|string'
        ],[
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.unique' => 'Já existe uma comissão com este nome.',
            'description.string' => 'A descrição deve ser um texto.',
        ]);

        $user = auth()->id();

        $committee = Committee::create([
            ...$validated,
            'created_by' => $user,
            'updated_by' => $user,
        ]);

        return redirect()->route('committees.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "A comissão <b>$request->name</b> foi criada com sucesso."
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
        $committee = Committee::find($id);

        if (!$committee || !auth()->user()->can('update', $committee)) {
            return redirect()->route('committees.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Esta comissão não existe ou você não pode alterá-la.'
            ]);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('committees', 'name')->ignore($committee->id)
            ],
            'description' => 'nullable|string'
        ],[
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.unique' => 'Já existe uma comissão com este nome.',
            'description.string' => 'A descrição deve ser um texto.',
        ]);

        $committee->update([
            ...$validated,
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('committees.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "Comissão atualizada com sucesso."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $committee = Committee::find($id);

        if (!$committee || !auth()->user()->can('delete', $committee)) {
            return redirect()->route('committees.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Esta comissão não existe ou você não pode alterá-la.'
            ]);
        }

        $committee->delete();

        return redirect()->route('committees.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "A comissão <b>$committee->name</b> foi apagada com sucesso."
        ]);
    }
}
