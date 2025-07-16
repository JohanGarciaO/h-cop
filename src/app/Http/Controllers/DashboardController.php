<?php

namespace App\Http\Controllers;
use App\Enums\RoomCleaningStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\StatisticsService;

class DashboardController extends Controller
{
    public function index(StatisticsService $statistics)
    {
        $totalRooms = $statistics->getTotalRooms();
        $totalGuests = $statistics->getTotalGuests();
        $totalHousekeepers = $statistics->getTotalHousekeepers();
        $totalOperators = $statistics->getTotalOperators();

        ['labels' => $diasDaSemana, 'values' => $reservasPorDia] = $statistics->getReservationsLast7Days();
        
        ['labels' => $quartosStatusLabels, 'values' => $quartosStatusValues] = $statistics->getRoomOccupancy();

        ['labels' => $roomStateLabels, 'values' => $roomStateValues] = $statistics->getRoomStateDistribution();
        
        ['labels' => $reservationStatusLabels, 'values' => $reservationStatusValues] = $statistics->getReservationStatusCounts();
        
        ['labels' => $genderLabels, 'values' => $genderValues] = $statistics->getGenderDistribution();
        
        ['labels' => $locationLabels, 'values' => $locationValues] = $statistics->getTopLocations();
        
        ['labels' => $committeeLabels, 'values' => $committeeValues] = $statistics->getCommittees();

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
