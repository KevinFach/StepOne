<?php

namespace App\Filament\Resources\Addresses;

use App\Filament\Resources\Addresses\Pages\CreateAddress;
use App\Filament\Resources\Addresses\Pages\EditAddress;
use App\Filament\Resources\Addresses\Pages\ListAddresses;
use App\Models\Address;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'dirección';

    protected static ?string $pluralModelLabel = 'direcciones';

    protected static ?string $slug = 'direcciones';

    protected static ?string $recordTitleAttribute = 'street';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idCostumer')
                    ->label('Cliente')
                    ->relationship('costumer', 'email')
                    ->searchable()
                    ->required(),
                TextInput::make('street')
                    ->label('Calle')
                    ->required()
                    ->maxLength(150),
                TextInput::make('number')
                    ->label('Número')
                    ->required()
                    ->maxLength(20),
                TextInput::make('colony')
                    ->label('Colonia')
                    ->required()
                    ->maxLength(100),
                TextInput::make('city')
                    ->label('Ciudad')
                    ->required()
                    ->maxLength(100),
                TextInput::make('state')
                    ->label('Estado')
                    ->required()
                    ->maxLength(100),
                TextInput::make('zip')
                    ->label('Código postal')
                    ->required()
                    ->length(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idAddres')
            ->columns([
                TextColumn::make('idAddres')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('street')
                    ->label('Calle')
                    ->searchable(),
                TextColumn::make('number')
                    ->label('Número'),
                TextColumn::make('colony')
                    ->label('Colonia'),
                TextColumn::make('city')
                    ->label('Ciudad'),
                TextColumn::make('zip')
                    ->label('CP'),
                TextColumn::make('costumer.email')
                    ->label('Cliente'),
            ])
            ->filters([
                SelectFilter::make('city')
                    ->label('Ciudad')
                    ->options(fn () => Address::distinct()->orderBy('city')->pluck('city', 'city')->all()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAddresses::route('/'),
            'create' => CreateAddress::route('/create'),
            'edit' => EditAddress::route('/{record}/edit'),
        ];
    }
}
