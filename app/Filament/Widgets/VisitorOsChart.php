<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\ChartWidget;

class VisitorOsChart extends ChartWidget
{
    protected static ?string $heading = "OS Pengunjung";
    public ?string $filter = 'month';

    public function updateFilter($newFilter)
    {
        $this->filter = $newFilter;
    }

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

        $data = Visitor::getVisitorOs($start, $end);

        $labels = [];
        $counts = [];
        foreach ($data as $entry) {
            $labels[] = $entry->os ?? 'Unknown';
            $counts[] = $entry->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Most OS Visitor',
                    'data' => $counts,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
