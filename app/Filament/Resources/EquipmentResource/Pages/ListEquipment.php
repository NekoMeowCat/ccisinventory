<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Resources\EquipmentResource;
use Filament\Actions;
use Filament\Actions\Action;
use App\Imports\EquipmentImport;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('importEquipment')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment'),
                ])
                ->action(function (array $data) {
                    // dd($data);
                    $file = public_path('storage/' . $data['attachment']);

                    // dd($file);

                    Excel::import(new EquipmentImport, $file);

                    Notification::make()
                        ->title('Equipment Imported')
                        ->success()
                        ->send();
                })
        ];
    }
}
