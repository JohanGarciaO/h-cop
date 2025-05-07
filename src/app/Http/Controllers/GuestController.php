<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Guest;
use App\Models\Address;

class GuestController extends Controller
{
    public function index(Request $request)
    {

        $guests = Guest::with(['address', 'reservations.room'])
        ->withCount([
            'reservations as active_reservations_count' => function ($query) {
                $query->whereNull('check_out_at');
            }
        ]);

        // Filtro por nome do hóspede
        if ($request->filled('name')) {
            $guests->where('name', 'like', '%' . $request->name . '%');
        }

        // Filtro por documento
        if ($request->filled('document')) {
            $guests->where('document', $request->document);
        }

        // Filtro por estado
        if ($request->filled('state_id')) {
            $guests->whereHas('address', function ($query) use ($request) {
                $query->where('state_id', $request->state_id);
            });
        }

        // Filtro por cidade
        if ($request->filled('city_id')) {
            $guests->whereHas('address', function ($query) use ($request) {
                $query->where('city_id', $request->city_id);
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            switch ($request->input('status')) {
                case 'unhosted':
                    $guests->having('active_reservations_count', '=', 0);
                    break;
                case 'check-in-pending':
                    $guests->havingRaw('active_reservations_count > 0');
                    $guests->whereHas('reservations', function($query) { $query->whereNull('check_in_at'); });
                    break;
                case 'check-out-pending':
                    $guests->havingRaw('active_reservations_count > 0');
                    $guests->whereHas('reservations', function($query) { $query->whereNull('check_out_at')->whereNotNull('check_in_at'); });
                    break;
            }
        }

        // Ordena por nome e pagina
        $guests = $guests->orderBy('name')->paginate(12)->appends($request->query());

        return view('guests.index', [
            'guests' => $guests,
            'result_count' => $guests->total(),
        ]);
    }

    public function create()
    {
        return view('guests.create');
    }

    public function store(Request $request)
    {
        $validatedGuest = $request->validate([
            'name' => 'required',
            'document' => 'required|min:14|unique:guests,document',
            'phone' => 'required|max:15',
            'email' => 'nullable|email',
        ],[
            'name.required' => 'o nome não pode estar vazio.',
            'document.required' => 'o CPF não pode estar vazio.',
            'document.max' => 'o CPF precisa ter ao menos 14 caracteres.',
            'document.unique' => 'Este documento já está vinculado a outro hóspede.',
            'phone.required' => 'o telefone não pode estar vazio.',
            'phone.max' => 'o telefone deve ter no máximo 15 caracteres.',
            'email.email' => 'O e-mail digitado não é válido',
        ]);

        $validatedAddress = $request->validate([
            'postal_code' => 'required|max:9',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'street' => 'required',
            'neighborhood' => 'required',
        ],[
            'postal_code.required' => 'O CEP não pode estar vazio.',
            'postal_code.max' => 'O CEP deve ter no máximo 9 caracteres.',
            'state_id.required' => 'O estado não pode estar vazio.',
            'state_id.exists' => 'O estado selecionado é inválido.',
            'city_id.required' => 'A cidade não pode estar vazia.',
            'city_id.exists' => 'A cidade selecionada é inválida.',
            'street.required' => 'A rua não pode estar vazia.',
            'neighborhood.required' => 'O bairro não pode estar vazio.',
        ]);

        $address = Address::create($validatedAddress);
        $guest = $address->guest()->create($validatedGuest);

        return redirect()->route('guests.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "O hóspede <b>$request->name</b> cadastrado com sucesso.",
        ]);
    }

    public function show(Guest $guest)
    {
        $guest->load(['address', 'reservations.room' => fn($q) => $q->select('id','number','capacity')]);
        // Pega a reserva ativa (caso exista)
        $activeReservation = $guest->reservations()->whereNull('check_out_at')->first();

        $status;
        if(!$activeReservation) {
            $status = 'Não hospedado';
        }else{
            $status = $activeReservation->status();
        }

        return view('guests.show', compact('guest', 'activeReservation', 'status'));
    }

    public function edit(Guest $guest)
    {
        $guest->load('address');
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $validatedGuest = $request->validate([
            'name' => 'required',
            'document' => [
                'required',
                'max:14',
                Rule::unique('guests', 'document')->ignore($guest->id),
            ],
            'phone' => 'required|max:15'
        ],[
            'name.required' => 'o nome não pode estar vazio.',
            'document.required' => 'o CPF não pode estar vazio.',
            'document.max' => 'o CPF precisa ter ao menos 14 caracteres.',
            'document.unique' => 'Este documento já está vinculado a outro hóspede.',
            'phone.required' => 'o telefone não pode estar vazio.',
            'phone.max' => 'o telefone deve ter no máximo 15 caracteres.',
            'email.email' => 'O e-mail digitado não é válido',
        ]);

        $validatedAddress = $request->validate([
            'postal_code' => 'required|max:9',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'street' => 'required',
            'neighborhood' => 'required',
        ],[
            'postal_code.required' => 'O CEP não pode estar vazio.',
            'postal_code.max' => 'O CEP deve ter no máximo 9 caracteres.',
            'state_id.required' => 'O estado não pode estar vazio.',
            'state_id.exists' => 'O estado selecionado é inválido.',
            'city_id.required' => 'A cidade não pode estar vazia.',
            'city_id.exists' => 'A cidade selecionada é inválida.',
            'street.required' => 'A rua não pode estar vazia.',
            'neighborhood.required' => 'O bairro não pode estar vazio.',
        ]);

        $guest->update($validatedGuest);
        $guest->address->update($validatedAddress);

        return redirect()->route('guests.index')->with([
            'status' => 'success',
            'alert-type' => 'success', 
            'message' => 'Hóspede atualizado com sucesso.',
        ]);
    }

    public function destroy(Guest $guest)
    {
        $name = $guest->name;
        $guest->delete();
        
        return redirect()->route('guests.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "O hóspede <b>$name</b> foi removido com sucesso.",
        ]);
    }
}
