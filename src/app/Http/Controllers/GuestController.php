<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\Models\Guest;
use App\Models\Address;
use App\Enums\Gender;

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
        if ($request->filled('name_filter')) {
            $guests->where('name', 'like', '%' . $request->name_filter . '%');
        }

        // Filtro por documento
        if ($request->filled('document_filter')) {
            $guests->where('document', $request->document_filter);
        }

        // Filtro por estado
        if ($request->filled('state_filter_id')) {
            $guests->whereHas('address', function ($query) use ($request) {
                $query->where('state_id', $request->state_filter_id);
            });
        }

        // Filtro por cidade
        if ($request->filled('city_filter_id')) {
            $guests->whereHas('address', function ($query) use ($request) {
                $query->where('city_id', $request->city_filter_id);
            });
        }

        // Filtro por gênero
        if ($request->filled('gender_filter')) {
            $guests->where('gender', $request->input('gender_filter'));
        }

        // Filtro por gênero
        if ($request->filled('committee_filter')) {
            $guests->where('committee_id', $request->input('committee_filter'));
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

    public function store(Request $request)
    {
        $validatedGuest = $request->validate([
            'name' => 'required',
            'document' => 'required|max:14|unique:guests,document',
            'phone' => 'required|max:15',
            'email' => 'nullable|email',
            'gender' => [
                'required',
                new Enum(Gender::class),
            ],
            'committee_id' => 'nullable|exists:committees,id',
        ],[
            'name.required' => 'o nome não pode estar vazio.',
            'document.required' => 'o CPF não pode estar vazio.',
            'document.max' => 'O tamanho máximo do documento é 11 dígitos numéricos.',
            'document.unique' => 'Este documento já está vinculado a outro hóspede.',
            'phone.required' => 'o telefone não pode estar vazio.',
            'phone.max' => 'o telefone deve ter no máximo 15 caracteres.',
            'email.email' => 'O e-mail digitado não é válido',
            'gender.required' => 'O gênero é obrigatório.',
            'gender.enum' => 'O gênero deve ser "Masculino" ou "Feminino".',
            'committee_id.exists' => 'Deve ser passada uma comitiva válida.',
        ]);

        $validatedGuest['document'] = Str::padLeft($validatedGuest['document'], 9, '0');

        $validatedAddress = $request->validate([
            'postal_code' => 'required|max:9',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'street' => 'required',
            'number' => 'nullable',
            'neighborhood' => 'required',
            'complement' => 'nullable',
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
        $guest = $address->guests()->create($validatedGuest);
        $guest->created_by = auth()->id();
        $guest->save();

        return redirect()->route('guests.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "O hóspede <b>$request->name</b> foi cadastrado com sucesso.",
        ]);
    }

    public function show(Guest $guest)
    {
        $guest->load(['address', 'reservations.room' => fn($q) => $q->select('id','number','capacity')]);
        // Pega a reserva ativa (caso exista)
        $activeReservation = $guest->reservations()->whereNull('check_out_at')->first();

        $status;
        if(!$activeReservation) {
            $status = 'não hospedado';
        }else{
            $status = $activeReservation->status;
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
        $rulesGuest = [
            'name' => 'required',
            'committee_id' => 'nullable|exists:committees,id',
            'phone' => 'required|max:15',
            'email' => 'nullable|email',
        ];

        $isAdmin = auth()->user()->isAdmin();

        // Apenas se for Admin pode alterar o documento e o gênero
        if ($isAdmin){
            $rulesGuest['document'] = [
                'required',
                'max:14',
                Rule::unique('guests', 'document')->ignore($guest->id),
            ];

            $rulesGuest['gender'] = [
                'required',
                new Enum(Gender::class),
            ];
        }

        $validatedGuest = $request->validate($rulesGuest,
        [
            'name.required' => 'o nome não pode estar vazio.',
            'document.required' => 'o CPF não pode estar vazio.',
            'document.max' => 'O tamanho máximo do documento é 11 dígitos numéricos.',
            'document.unique' => 'Este documento já está vinculado a outro hóspede.',
            'phone.required' => 'o telefone não pode estar vazio.',
            'phone.max' => 'o telefone deve ter no máximo 15 caracteres.',
            'email.email' => 'O e-mail digitado não é válido',
            'gender.required' => 'O gênero é obrigatório.',
            'gender.enum' => 'O gênero deve ser "Masculino" ou "Feminino".',
            'committee_id.exists' => 'Deve ser passada uma comitiva válida.',
        ]);

        $validatedGuest['document'] = Str::padLeft($validatedGuest['document'], 9, '0');

        $validatedAddress = $request->validate([
            'postal_code' => 'required|max:9',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'street' => 'required',
            'number' => 'nullable',
            'neighborhood' => 'required',
            'complement' => 'nullable',
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

        // Garante que o operador nunca terá acesso ao documento e gênero para edição
        $guest->update($isAdmin ? $validatedGuest : Arr::except($validatedGuest, ['document', 'gender']));

        $guest->address->update($validatedAddress);
        $guest->updated_by = auth()->id();
        $guest->save();

        return redirect()->route('guests.edit', $guest)->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => 'Hóspede atualizado com sucesso.',
        ]);
    }

    public function destroy(Guest $guest)
    {
        // No nível de operador não é mais possível apagar hóspedes
        if (!auth()->user()->can('delete', $guest)) {
            return redirect()->route('guests.index')->with([
                'status' => 'error',
                'alert-type' => 'danger',
                'message' => "Você não pode apagar hóspedes.",
            ]);
        }

        $name = $guest->name;
        $guest->delete();

        return redirect()->route('guests.index')->with([
            'status' => 'success',
            'alert-type' => 'success',
            'message' => "O hóspede <b>$name</b> foi removido com sucesso.",
        ]);
    }
}
