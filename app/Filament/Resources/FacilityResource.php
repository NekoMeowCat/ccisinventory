<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Filament\Resources\FacilityResource\RelationManagers;
use App\Models\Facility;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Infolists\Infolist;
use App\Models\BorrowList;
use Filament\Forms\FormsComponent;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Facility Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->placeholder('Facility Name Displayed On The Door (e.g., CL1, CL2)')
                                    ->maxLength(255),
                                Forms\Components\Select::make('connection_type')
                                    ->options([
                                        'None' => 'None',
                                        'Wi-Fi' => 'Wi-Fi',
                                        'Ethernet' => 'Ethernet',
                                        'Both Wi-fi and Ethernet' => 'Both Wi-fi and Ethernet',
                                        'Fiber Optic' => 'Fiber Optic',
                                        'Cellular' => 'Cellular',
                                        'Bluetooth' => 'Bluetooth',
                                        'Satellite' => 'Satellite',
                                        'DSL' => 'DSL',
                                        'Cable' => 'Cable',
                                    ]),
                                Forms\Components\Select::make('facility_type')
                                    ->options([
                                        'Room' => 'Room',
                                        'Office' => 'Office',
                                        'Computer Laboratory' => 'Computer Laboratory',
                                        'Incubation Hub' => 'Incubation Hub',
                                        'Robotic Hub' => 'Robotic Hub',
                                        'Hall' => 'Hall',
                                    ]),
                                Forms\Components\Select::make('cooling_tools')
                                    ->options([
                                        'None' => 'None',
                                        'Aircon' => 'Aircon',
                                        'Ceiling Fan' => 'Ceiling Fan',
                                        'Both Aircon and Ceiling Fan' => 'Both Aircon and Ceiling Fan',
                                    ]),
                                Forms\Components\Select::make('floor_level')
                                    ->options([
                                        '1' => '1st Floor',
                                        '2' => '2nd Floor',
                                        '3' => '3rd Floor',
                                        '4' => '4th Floor',
                                    ]),
                                Forms\Components\TextInput::make('building')
                                    ->disabled()
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
                            ->placeholder('Anything that describes the facility (e.g., Computer Laboratory with space for 30 students)')
                            ->required()
                            ->disableToolbarButtons([
                                'attachFiles'
                            ])
                    ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        $user = auth()->user();
        $isPanelUser = $user && $user->hasRole('panel_user');

        // Define the bulk actions array
        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
            Tables\Actions\BulkAction::make('add_to_borrow_list')
                ->label('Add to Request List')
                ->icon('heroicon-o-shopping-cart')
                ->action(function (Collection $records) {
                    foreach ($records as $record) {
                        BorrowList::updateOrCreate(
                            [
                                'user_id' => auth()->id(),
                                'facility_id' => $record->id, // Correctly reference the facility ID here
                            ]
                        );
                    }

                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Selected facilities have been added to your borrow list.')
                        ->send();
                })
                ->color('primary')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-check')
                ->modalHeading('Add to Borrow List')
                ->modalDescription('Confirm to add selected facilities to your borrow list'),
        ];

        // Conditionally add ExportBulkAction
        if (!$isPanelUser) {
            $bulkActions[] = ExportBulkAction::make();
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('connection_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('facility_type')
                    ->label('Facility Type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('floor_level')
                    ->label('Floor Level')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('building')
                    ->searchable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->searchable()
                    ->html(),
            ])
            ->filters([
                // Your filters here
            ])
            ->recordUrl(function ($record) {
                return Pages\ViewFacility::getUrl([$record->id]);
            })
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->color('warning'),
                    Tables\Actions\Action::make('viewEquipment')
                        ->label('View Equipment')
                        ->icon('heroicon-o-cog')
                        ->color('success')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->slideOver()
                        ->modalHeading('Equipment List')
                        ->modalContent(function ($record) {
                            $equipment = Equipment::where('facility_id', $record->id)->paginate(10);

                            return view('filament.resources.facility-equipment-modal', [
                                'equipment' => $equipment,
                            ]);
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make($bulkActions)
                    ->label('Actions')
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
                        TextEntry::make('remarks')
                            ->html(),
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
            'view' => Pages\ViewFacility::route('/{record}'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}
