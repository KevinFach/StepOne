<?php

namespace App\Filament\Resources\Carts;

use App\Filament\Resources\Carts\Pages\CreateCart;
use App\Filament\Resources\Carts\Pages\EditCart;
use App\Filament\Resources\Carts\Pages\ListCarts;
use App\Filament\Resources\Carts\RelationManagers\DetailsRelationManager;
use App\Models\Cart;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CartResource extends Resource
{
    public const STATUSES = [
        'active' => 'Activo',
        'completed' => 'Completado',
        'expired' => 'Expirado',
    ];

    protected static ?string $model = Cart::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static string|UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'carrito';

    protected static ?string $pluralModelLabel = 'carritos';

    protected static ?string $slug = 'carritos';

    protected static ?string $recordTitleAttribute = 'idCart';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idCostumer')
                    ->label('Cliente')
                    ->relationship('costumer', 'email')
                    ->searchable()
                    ->required(),
                Select::make('status')
                    ->label('Estado')
                    ->options(self::STATUSES)
                    ->default('active')
                    ->required(),
                DateTimePicker::make('createdAt')
                    ->label('Creado')
                    ->default(now()),
                DateTimePicker::make('expiresAt')
                    ->label('Expira')
                    ->default(now()->addDays(7))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idCart')
            ->columns([
                TextColumn::make('idCart')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('costumer.full_name')
                    ->label('Costumer'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'active' => 'success',
                        'completed' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('createdAt')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('expiresAt')
                    ->label('Expira')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(self::STATUSES),
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

    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarts::route('/'),
            'create' => CreateCart::route('/create'),
            'edit' => EditCart::route('/{record}/edit'),
        ];
    }
}
