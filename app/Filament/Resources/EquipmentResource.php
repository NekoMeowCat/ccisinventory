<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Filament\Resources\EquipmentResource\RelationManagers;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Equipment Details')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->maxLength(255),
                                Forms\Components\Select::make('facility_id')
                                    ->relationship('facility', 'name')
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required(),
                                Forms\Components\TextInput::make('unit_no')
                                    ->label('Unit Number')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('description')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('specifications')
                                    ->maxLength(255),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'Working' => 'Working',
                                        'For Repair' => 'For Repair',
                                        'For Replacement' => 'For Replacement',
                                        'Lost' => 'Lost',
                                        'For Disposal' => 'For Disposal',
                                        'Disposed' => 'Disposed',
                                        'Borrowed' => 'Borrowed',
                                    ])
                                    ->native(false),
                                Forms\Components\TextInput::make('date_acquired')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('supplier')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('amount')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('estimated_life')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('item_no')
                                    ->label('Item Number')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('property_no')
                                    ->label('Property Number')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('control_no')
                                    ->label('Control Number')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('serial_no')
                                    ->label('Serial Number')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('no_of_stocks')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('restocking_point')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('person_liable')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('remarks')
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('facility.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('specifications')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Working' => 'success',
                        'For Repair' => 'warning',
                        'For Replacement' => 'primary',
                        'Lost' => 'danger',
                        'For Disposal' => 'primary',
                        'Disposed' => 'danger',
                        'Borrowed' => 'indigo',
                    }),
                Tables\Columns\TextColumn::make('date_acquired')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estimated_life')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('property_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('control_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serial_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_of_stocks')
                    ->searchable(),
                Tables\Columns\TextColumn::make('restocking_point')
                    ->searchable(),
                Tables\Columns\TextColumn::make('person_liable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
