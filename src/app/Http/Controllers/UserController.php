<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', auth()->user());

        $users = User::with('role');

        // Filtro por username
        if ($request->filled('username_filter')) {
            $users->where('username', 'like', '%' . $request->username_filter . '%');
        }

        // Filtro por nome
        if ($request->filled('name_filter')) {
            $users->where('name', 'like', '%' . $request->name_filter . '%');
        }

        // Filtro por documento
        if ($request->filled('document_filter')) {
            $users->where('document', $request->document_filter);
        }

        $users = $users->orderBy('role_id')->orderBy('name')->paginate(12)->appends($request->query());

        return view('users.index',[
            'users' => $users,
            'result_count' => $users->total()
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
            'role_id' => 'required|integer|exists:roles,id',
            'username' => 'required|string|unique:users',
            'name' => 'required|string',
            'document' => 'required|max:14|unique:users',
            'phone' => 'nullable|string|max:15',
            'email' => 'required|email|unique:users'
        ],[
            'role_id.required' => 'O papel do usuário é obrigatório.',
            'role_id.integer' => 'O papel do usuário deve ser um id numérico.',
            'role_id.exists' => 'O papel do usuário informado é inválido.',
            'username.required' => 'O nome de usuário é obrigatório.',
            'username.string' => 'O nome de usuário deve ser um texto.',
            'username.unique' => 'Este nome de usuário já está em uso.',
            'name.required' => 'O nome completo é obrigatório.',
            'name.string' => 'O nome deve ter um formato textual.',
            'document.required' => 'O documento é obrigatório.',
            'document.max' => 'O tamanho máximo do documento é 11 dígitos numéricos.',
            'document.unique' => 'Este documento já está vinculado a outro usuário.',
            'phone.max' => 'O telefone só pode ter no máximo 15 números.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',
            'email.unique' => 'Este e-mail já está em uso.',
        ]);

        $validated['document'] = Str::padLeft($validated['document'], 9, '0');   
        $validated['email_verified_at'] = now();

        // Criação do usuário com senha padrão
        $user = new User();
        $user->fill($validated);
        
        $user->password = Hash::make(config('auth.default_password','hcop*123'));
        $user->save();

        return redirect()->route('users.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'O usuário <b>' . $user->username . '</b> foi criado com sucesso.'
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
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Este usuário não existe.',
            ]);
        }

        $validated = $request->validate([
            'role_id' => 'nullable|integer|exists:roles,id',
            'username' => [
                'nullable',
                'string',
                Rule::unique('users')->ignore($user->id)
            ],
            'name' => 'nullable|string',
            'document' => [
                'nullable',
                'max:14',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:15',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
        ],[
            'role_id.integer' => 'O papel do usuário deve ser um id numérico.',
            'role_id.exists' => 'O papel do usuário informado é inválido.',
            'username.string' => 'O nome de usuário deve ser um texto.',
            'username.unique' => 'Este nome de usuário já está em uso.',
            'name.string' => 'O nome deve ter um formato textual.',
            'document.max' => 'O tamanho máximo do documento é 11 dígitos numéricos.',
            'document.unique' => 'Este documento já está vinculado a outro usuário.',
            'phone.max' => 'O telefone só pode ter no máximo 15 números.',
            'email.email' => 'O e-mail informado não é válido.',
            'email.unique' => 'Este e-mail já está em uso.',
        ]);

        $validated['document'] = Str::padLeft($validated['document'], 9, '0');   

        $user->update([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('users.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'O usuário <b>' . $user->username . '</b> foi atualizado com sucesso.'
        ]);
    }

    public function reset(string $id) 
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Este usuário não existe.',
            ]);
        }

        try {
            $user->password = Hash::make(config('auth.default_password', 'hcop*123'));
            // $user->updated_by = auth()->id();
            $user->save();
        } catch (\Throwable $th) {
            \Log::error('Erro ao remover usuário: ' . $th->getMessage());
           return redirect()->route('users.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Erro ao apagar usuário: ' . $th->getMessage() . '.',
            ]);
        }

        return redirect()->route('users.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'A senha do usuário <b>' . $user->username . '</b> foi resetada com sucesso.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Este usuário não existe.',
            ]);
        }

        try {
            $user->delete();
        } catch (\Throwable $th) {
            \Log::error('Erro ao remover usuário: ' . $th->getMessage());
           return redirect()->route('users.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => 'Erro ao apagar usuário: ' . $th->getMessage() . '.',
            ]);
        }

        return redirect()->route('users.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'O usuário <b>' . $user->username . '</b> foi apagado do sistema.'
        ]);
    }
}
