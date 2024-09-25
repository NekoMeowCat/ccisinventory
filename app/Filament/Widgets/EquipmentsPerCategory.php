<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Category;
use App\Models\Equipment;
use Carbon\Carbon;

class EquipmentsPerCategory extends ChartWidget
{
    protected static ?string $heading = 'Equipment Per Category';

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

        // Fetch categories and their equipment count within the selected date range
        $categoryCounts = Equipment::selectRaw('category_id, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('category_id')
            ->pluck('count', 'category_id')
            ->toArray();

        $categories = Category::all();

        // Prepare labels and data for the chart
        $labels = [];
        $data = [];

        foreach ($categories as $category) {
            $labels[] = $category->name;  // Assuming the 'name' field is the category name
            $data[] = $categoryCounts[$category->id] ?? 0;  // Default to 0 if no equipment in that category
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
