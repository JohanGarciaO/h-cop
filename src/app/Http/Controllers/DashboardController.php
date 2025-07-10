<?php

namespace App\Http\Controllers;

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

        $quartosPorStatus = ['Vazio' => 4, 'Ocupado' => 8];
        $quartosPorEstado = ['Pronto' => 6, 'Em Preparo' => 3, 'Manutenção' => 3];
        $reservasPorStatus = ['Check-in pendente' => 2, 'Check-out pendente' => 5];
        $generoHospedes = ['Masculino' => 12, 'Feminino' => 9, 'Outro' => 3];
        $localidadesHospedes = ['SP' => 10, 'RJ' => 5, 'MG' => 6, 'BA' => 2];
        $comitivasHospedes = ['Marinha' => 12, 'Exército' => 6, 'Aeronáutica' => 4];

        return view('home.index', compact(
            'totalRooms',
            'totalGuests',
            'totalHousekeepers',
            'totalOperators',
            'diasDaSemana',
            'reservasPorDia',
            'quartosPorStatus',
            'quartosPorEstado',
            'reservasPorStatus',
            'generoHospedes',
            'localidadesHospedes',
            'comitivasHospedes',
        ));
    }
}
