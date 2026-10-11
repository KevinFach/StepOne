<?php

namespace App\Filament\Resources\DetailOrders;

use App\Filament\Resources\DetailOrders\Pages\CreateDetailOrder;
use App\Filament\Resources\DetailOrders\Pages\EditDetailOrder;
use App\Filament\Resources\DetailOrders\Pages\ListDetailOrders;
use App\Filament\Resources\SneakerVariants\SneakerVariantResource;
use App\Models\DetailOrder;
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

class DetailOrderResource extends Resource
{
    protected static ?string $model = DetailOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static string|UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'detalle de pedido';

    protected static ?string $pluralModelLabel = 'detalles de pedido';

    protected static ?string $slug = 'detalles-pedido';

    protected static ?string $recordTitleAttribute = 'idDetailOrder';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idOrder')
                    ->label('Pedido')
                    ->relationship('order', 'idOrder')
                    ->searchable()
                    ->required(),
                SneakerVariantResource::variantSelect(),
                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required()
                    ->integer()
                    ->minValue(1),
                TextInput::make('unitPrice')
                    ->label('Precio unitario')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idDetailOrder')
            ->columns([
                TextColumn::make('idDetailOrder')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->searchable(),
                TextColumn::make('unitPrice')
                    ->label('Precio unitario')
                    ->money('MXN')
                    ->searchable(),
                TextColumn::make('variant.label')
                    ->label('Variante'),
                TextColumn::make('idOrder')
                    ->label('Pedido')
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
            'index' => ListDetailOrders::route('/'),
            'create' => CreateDetailOrder::route('/create'),
            'edit' => EditDetailOrder::route('/{record}/edit'),
        ];
    }
}
