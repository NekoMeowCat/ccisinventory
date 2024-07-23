<?php

namespace App\Filament\User\Resources\BorrowResource\Pages;

use App\Filament\User\Resources\BorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBorrow extends CreateRecord
{
    protected static string $resource = BorrowResource::class;
}
