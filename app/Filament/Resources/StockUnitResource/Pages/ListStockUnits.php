<?php

namespace App\Filament\Resources\StockUnitResource\Pages;

use App\Filament\Resources\StockUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;


class ListStockUnits extends ListRecords
{
    protected static string $resource = StockUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn() => !Auth::user()->hasRole('panel_user')),
        ];
    }
}
