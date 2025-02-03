<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\ChartWidget;

class CountryVisitor extends ChartWidget
{
    protected static ?string $heading = 'Negara Pengunjung';
    protected static ?int $sort = 2;
    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'month' => date('F Y'),
            'year' => 'Total in ' . date('Y'),
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $start = match ($activeFilter) {
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfYear(),
        };
        $end = now()->endOfYear();

        $data = Visitor::getCountries($start, $end);

        $labels = [];
        $backgroundColors = [
            '#36A2EB',
            '#FF6384',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40',
            '#8E44AD',
            '#1ABC9C',
            '#F39C12',
            '#D35400',
            '#2ECC71',
            '#C0392B',
            '#7F8C8D',
            '#34495E',
            '#9B59B6',
            '#27AE60',
            '#E67E22',
            '#2980B9',
            '#BDC3C7',
            '#16A085',
            '#E74C3C',
            '#95A5A6',
            '#F1C40F',
            '#2C3E50',
            '#6C5CE7',
            '#00CEC9',
            '#FAB1A0',
            '#81ECEC',
            '#FFEAA7',
            '#74B9FF'
        ];
        $counts = [];
        foreach ($data as $entry) {
            $labels[] = $entry->country ?? 'Unknown';
            $counts[] = $entry->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Country visitor',
                    'data' => $counts,
                    'backgroundColor' => array_slice($backgroundColors, 0, count($data)),
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
