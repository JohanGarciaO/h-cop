<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Guest;

class UtilsController extends Controller
{
    public function showAvailableBetween(Request $request, $entity)
    {
        // Validação manual do tipo de entidade
        if (!in_array($entity, ['room', 'guest'])) {
            return response()->json([
                'error' => 'Este método só está disponível para room e guest.'
            ]);
        }

        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ],[
            'check_in.required' => 'Deve ser passada uma data inicial.',
            'check_in.date' => 'Deve ser passada uma data válida.',
            'check_out.required' => 'Deve ser passada uma data final.',
            'check_out.date' => 'Deve ser passada uma data válida.',
            'check_out.after' => 'A data final deve ser posterior à data inicial.',
        ]); 

        $results;
        if ($entity == 'room') {
            $results = Room::withCount(['activeReservations']);
        }else{
            $results = Guest::withCount(['activeReservations']);
        }

        $exceptReservationId = request('except_reservation_id') ?? '';

        // Filtro por disponibilidade entre datas e ordenação
        if ($entity === 'room' && $request->check_in && $request->check_out) {
            $results->availableBetween($request->check_in, $request->check_out, $exceptReservationId);
            $results = $results->orderBy('number')->get();
        } else if ($entity === 'guest' && $request->check_in && $request->check_out) {
            $results->availableBetween($request->check_in, $request->check_out, $exceptReservationId);
            $results = $results->orderBy('name')->get();
        }        

        return response()->json([
            'data' => $results,
        ]);
    }
}
