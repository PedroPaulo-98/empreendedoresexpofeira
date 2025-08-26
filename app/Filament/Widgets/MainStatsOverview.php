<?php

namespace App\Filament\Widgets;

use App\Models\DailyData;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class MainStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $user = Auth::user();
        $isEmpresa = $user?->hasRole('empresa') ?? false;

        $totalIncome = DailyData::query()
            ->when($isEmpresa, function ($query) use ($user) {
                $query->whereHas('company', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->selectRaw('COALESCE(SUM(CAST(daily_income as DECIMAL(15,2))), 0) as total')
            ->value('total');

        $formattedIncome = 'R$ ' . number_format((float) $totalIncome, 2, ',', '.');

        $totalEmpresaUsers = User::role('empresa')->count();

        return [
            Card::make('Faturamento total', $formattedIncome),
            Card::make('Usuários (empresa)', (string) $totalEmpresaUsers),
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()?->hasRole('super_admin') ?? false;
    }
} 