<?php

namespace App\Filament\Resources\DetailCarts;

use App\Filament\Resources\DetailCarts\Pages\CreateDetailCart;
use App\Filament\Resources\DetailCarts\Pages\EditDetailCart;
use App\Filament\Resources\DetailCarts\Pages\ListDetailCarts;
use App\Filament\Resources\SneakerVariants\SneakerVariantResource;
use App\Models\DetailCart;
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
use Filament\Tables\Table;
use UnitEnum;

class DetailCartResource extends Resource
{
    protected static ?string $model = DetailCart::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static string|UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'detalle de carrito';

    protected static ?string $pluralModelLabel = 'detalles de carrito';

    protected static ?string $slug = 'detalles-carrito';

    protected static ?string $recordTitleAttribute = 'idDetailCart';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idCart')
                    ->label('Carrito')
                    ->relationship('cart', 'idCart')
                    ->searchable()
                    ->required(),
                SneakerVariantResource::variantSelect(),
                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required()
                    ->integer()
                    ->minValue(1),
                TextInput::make('priceAtAddition')
                    ->label('Precio al agregar')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idDetailCart')
            ->columns([
                TextColumn::make('idDetailCart')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->searchable(),
                TextColumn::make('priceAtAddition')
                    ->label('Precio al agregar')
                    ->money('MXN')
                    ->searchable(),
                TextColumn::make('variant.label')
                    ->label('Variante'),
                TextColumn::make('idCart')
                    ->label('Carrito')
                    ->sortable(),
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
            'index' => ListDetailCarts::route('/'),
            'create' => CreateDetailCart::route('/create'),
            'edit' => EditDetailCart::route('/{record}/edit'),
        ];
    }
}
