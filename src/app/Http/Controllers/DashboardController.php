<?php

namespace App\Http\Controllers;
use App\Enums\RoomCleaningStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRooms = 12;
        $totalGuests = 35;
        $totalHousekeepers = 3;
        $totalOperators = 4;

        $diasDaSemana = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'];
        $reservasPorDia = [3, 5, 2, 6, 7, 1, 4];

        $quartosStatusLabels = ['Ocupados', 'Vazios'];
        $quartosStatusValues = [4, 8];

        $roomStateLabels = [
            Str::of(RoomCleaningStatus::READY->label())->apa(),
            Str::of(RoomCleaningStatus::IN_PREPARATION->label())->apa(),
            Str::of(RoomCleaningStatus::NEEDS_MAINTENANCE->label())->apa()
        ];
        $roomStateValues = [6,3,1];

        $reservationStatusLabels = ['Check-in Pendente', 'Check-out Pendente'];
        $reservationStatusValues = [15, 8];

        $genderLabels = ['Masculino', 'Feminino'];
        $genderValues = [18, 12];

        $locationLabels = ['São Paulo', 'Rio de Janeiro', 'Bahia', 'Paraná', 'Outros'];
        $locationValues = [10, 8, 6, 4, 3];

        $committeeLabels = ['Exército', 'Marinha', 'Aeronáutica', 'Civil'];
        $committeeValues = [12, 5, 4, 9];

        return view('home.index', compact(
            'totalRooms',
            'totalGuests',
            'totalHousekeepers',
            'totalOperators',
            'diasDaSemana',
            'reservasPorDia',
            'quartosStatusLabels',
            'quartosStatusValues',
            'roomStateLabels',
            'roomStateValues',
            'reservationStatusLabels',
            'reservationStatusValues',
            'genderLabels',
            'genderValues',
            'locationLabels',
            'locationValues',
            'committeeLabels',
            'committeeValues',
        ));
    }
}
