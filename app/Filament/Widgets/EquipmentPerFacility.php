<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Equipment;
use App\Models\Facility;
use Carbon\Carbon;

class EquipmentPerFacility extends ChartWidget
{
    protected static ?string $heading = 'Equipments Per Facility';

    public ?string $filter = 'month'; // Set default filter value

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $startDate = null;
        $endDate = Carbon::now();

        switch ($activeFilter) {
            case 'today':
                $startDate = Carbon::today();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                break;
        }

        // Filter Equipment records by the selected date range
        $equipmentCounts = Equipment::selectRaw('facility_id, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('facility_id')
            ->pluck('count', 'facility_id')
            ->toArray();

        $facilities = Facility::all();

        // Prepare labels and data for the chart
        $labels = [];
        $data = [];

        foreach ($facilities as $facility) {
            $labels[] = $facility->name;  // Assuming the 'name' field is the facility name
            $data[] = $equipmentCounts[$facility->id] ?? 0;  // Default to 0 if no equipment is found for that facility
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Equipments',
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
