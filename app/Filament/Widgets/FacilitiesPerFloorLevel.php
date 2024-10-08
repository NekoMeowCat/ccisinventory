<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Facility;

class FacilitiesPerFloorLevel extends ChartWidget
{
    protected static ?string $heading = 'Facility Floor Level';

    protected function getData(): array
    {

        $floorLevelCounts = Facility::selectRaw('floor_level, COUNT(*) as count')
            ->groupBy('floor_level')
            ->pluck('count', 'floor_level')
            ->toArray();

        // Prepare labels and data for the chart
        $labels = array_keys($floorLevelCounts);
        $data = array_values($floorLevelCounts);

        return [
            'datasets' => [
                [
                    'label' => 'Floor Level',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(201, 203, 207, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)',
                    ],
                    'borderWidth' => 1,
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
