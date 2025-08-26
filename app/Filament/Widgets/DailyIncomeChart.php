<?php

namespace App\Filament\Widgets;

use App\Models\DailyData;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class DailyIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Faturamento por dia';

    protected function getData(): array
    {
        $user = Auth::user();
        $isEmpresa = $user?->hasRole('empresa') ?? false;

        $rows = DailyData::query()
            ->when($isEmpresa, function ($query) use ($user) {
                $query->whereHas('company', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->selectRaw('date, SUM(CAST(daily_income as DECIMAL(15,2))) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $rows->pluck('date')->map(fn($d) => 
            \Carbon\Carbon::parse($d)->format('d/m')
        )->toArray();
        $data = $rows->pluck('total')->map(fn($v) => (float) $v)->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Faturamento',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'tension' => 0.3,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        return Auth::user()?->hasRole('super_admin') ?? false;
    }
} 