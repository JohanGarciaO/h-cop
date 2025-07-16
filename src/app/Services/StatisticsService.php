<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Enums\RoomCleaningStatus;
use App\Enums\Role;
use App\Models\Room;
use App\Models\User;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Cleaning;
use App\Models\Housekeeper;

class StatisticsService
{
    public function getTotalRooms(): int
    {
        return Room::count();
    }

    public function getTotalGuests(): int
    {
        return Guest::count();
    }

    public function getTotalHousekeepers(): int
    {
        return Housekeeper::count();
    }

    public function getTotalOperators(): int
    {
        return User::where('role_id', Role::OPERATOR->value)->count();
    }

    public function getReservationsLast7Days(): array
    {
        $start = now()->subDays(6)->startOfDay();
        $end = now()->endOfDay();

        // Cria um array com os últimos 7 dias (Date => Dia da semana)
        $days = collect();
        for ($i = 0; $i < 7; $i++) {
            $date = now()->subDays(6 - $i)->startOfDay();
            $days->put($date->toDateString(), ucfirst($date->translatedFormat('D')));
        }

        // Busca as reservas agrupadas por data
        $data = Reservation::selectRaw('DATE(check_in_at) as date, count(*) as total')
            ->whereBetween('check_in_at', [$start, $end])
            ->groupBy('date')
            ->pluck('total', 'date');

        // Preenche as datas faltantes com 0
        $values = $days->map(fn ($_, $date) => $data[$date] ?? 0)->values()->toArray();
        $labels = $days->values()->toArray();

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    public function getRoomOccupancy(): array
    {
        $occupied = Room::whereHas('reservations', function ($q) {
            $q->whereNull('check_out_at')->whereNotNull('check_in_at');
        })->count();

        $total = Room::count();
        $vacant = $total - $occupied;

        return [
            'labels' => ['Ocupados', 'Vazios'],
            'values' => [$occupied, $vacant]
        ];
    }

    public function getRoomStateDistribution(): array
    {
        $counts = RoomCleaningStatus::cases();
        $values = [];

        foreach ($counts as $status) {
            $values[] = Room::whereHas('lastCleaning', fn($q) =>
                $q->where('status', $status->name)
            )->count();
        }

        return [
            'labels' => [
                Str::of(RoomCleaningStatus::READY->label())->apa(),
                Str::of(RoomCleaningStatus::IN_PREPARATION->label())->apa(),
                Str::of(RoomCleaningStatus::NEEDS_MAINTENANCE->label())->apa()
            ],
            'values' => $values,
        ];
    }

    public function getReservationStatusCounts(): array
    {
        $checkinPending = Reservation::whereNull('check_in_at')
            ->whereDate('scheduled_check_in', '<=', today())
            ->count();

        $checkoutPending = Reservation::whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->count();

        return [
            'labels' => ['Check-in Pendente', 'Check-out Pendente'],
            'values' => [$checkinPending, $checkoutPending]
        ];
    }

    public function getGenderDistribution(): array
    {
        $guests = Guest::whereHas('reservations', fn ($q) => $q->active())->get();

        $grouped = $guests
            ->map(fn ($guest) => $guest->gender?->label() ?? 'Não informado')
            ->groupBy(fn ($label) => $label)
            ->map(fn ($group) => count($group))
            ->sortDesc();

        return [
            'labels' => $grouped->keys()->toArray(),
            'values' => $grouped->values()->toArray()
        ];
    }

    public function getTopLocations($limit = 5): array
    {
        $guests = Guest::whereHas('reservations', fn ($q) => $q->active())
            ->with('address.state')
            ->get();

        $grouped = $guests
            ->map(fn ($guest) => $guest->address?->state?->name ?? 'Não informado')
            ->groupBy(fn ($state) => $state)
            ->map(fn ($items) => count($items))
            ->sortDesc();

        if ($grouped->count() <= 4) {
            return [
                'labels' => $grouped->keys()->toArray(),
                'values' => $grouped->values()->toArray()
            ];
        }

        $top = $grouped->take($limit - 1);
        $restTotal = $grouped->slice($limit - 1)->sum();

        return [
            'labels' => [...$top->keys(), 'Outros'],
            'values' => [...$top->values(), $restTotal],
        ];
    }

    public function getCommittees(): array
    {
        $guests = Guest::whereHas('reservations', fn ($q) => $q->active())
            ->with('committee')
            ->get();

        $grouped = $guests 
            ->groupBy(fn ($guest) => optional($guest->committee)->name ?? 'Não informado')
            ->map(fn ($group) => $group->count())
            ->sortDesc();

        return [
            'labels' => $grouped->keys()->toArray(),
            'values' => $grouped->values()->toArray(),
        ];
    }
}