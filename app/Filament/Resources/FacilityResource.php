<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Filament\Resources\FacilityResource\RelationManagers;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists\Infolist;
// use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components;
use Filament\Infolists\Components\TextEntry;





class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Facility Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('connection_type')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('facility_type')
                                    ->label('Facility Type')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('cooling_tools')
                                    ->label('Cooling Tools')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('floor_level')
                                    ->label('Floor Level')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('building')
                                    ->required()
                                    ->maxLength(255)
                                    ->default('HIRAYA'),
                            ]),
                    ]),
                Section::make('Facility Image')
                    ->schema([
                        Forms\Components\FileUpload::make('facility_img')
                            ->image()
                            ->label('Facility Image')
                            ->imageEditor()
                            ->disk('public')
                            ->directory('facility'),
                    ]),
                Section::make('Remarks')
                    ->schema([
                        Forms\Components\RichEditor::make('remarks')
                            ->required()
                            ->disableToolbarButtons([
                                'attachFiles'
                            ])
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('facility_type')
                    ->label('Facility Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('floor_level')
                    ->label('Floor Level')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ImageEntry::make('facility_img')
                    ->label('Image')
                    ->columnSpanFull()
                    ->width(200)
                    ->height(200),
                Components\Grid::make([
                    'default'   => 1,
                    'sm'        => 2,
                    'md'        => 3,
                ])
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('connection_type'),
                        TextEntry::make('cooling_tools'),
                        TextEntry::make('floor_level'),
                        TextEntry::make('building'),
                        TextEntry::make('remarks'),
                    ])

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
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}
