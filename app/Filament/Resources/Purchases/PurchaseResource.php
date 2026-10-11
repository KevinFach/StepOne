<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\Pages\CreatePurchase;
use App\Filament\Resources\Purchases\Pages\EditPurchase;
use App\Filament\Resources\Purchases\Pages\ListPurchases;
use App\Models\Purchase;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|UnitEnum|null $navigationGroup = 'Inventario';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'compra';

    protected static ?string $pluralModelLabel = 'compras';

    protected static ?string $slug = 'compras';

    protected static ?string $recordTitleAttribute = 'invoiceNumber';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoiceNumber')
                    ->label('Número de factura')
                    ->required()
                    ->maxLength(100),
                TextInput::make('totalAmount')
                    ->label('Total')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('paymentMethod')
                    ->label('Método de pago')
                    ->required()
                    ->maxLength(50),
                DateTimePicker::make('purchaseDate')
                    ->label('Fecha de compra')
                    ->default(now()),
                Select::make('idSupplier')
                    ->label('Proveedor')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('idEmployee')
                    ->label('Empleado')
                    ->relationship('employee', 'email')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idPurchase')
            ->columns([
                TextColumn::make('idPurchase')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('invoiceNumber')
                    ->label('Factura')
                    ->searchable(),
                TextColumn::make('totalAmount')
                    ->label('Total')
                    ->money('MXN')
                    ->searchable(),
                TextColumn::make('paymentMethod')
                    ->label('Método de pago'),
                TextColumn::make('purchaseDate')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('supplier.name')
                    ->label('Proveedor'),
                TextColumn::make('employee.email')
                    ->label('Empleado'),
            ])
            ->filters([
                SelectFilter::make('paymentMethod')
                    ->label('Método de pago')
                    ->options(fn () => Purchase::distinct()->orderBy('paymentMethod')->pluck('paymentMethod', 'paymentMethod')->all()),
                Filter::make('purchaseDate')
                    ->label('Fecha de compra')
                    ->schema([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['desde'], fn ($q, $date) => $q->whereDate('purchaseDate', '>=', $date))
                        ->when($data['hasta'], fn ($q, $date) => $q->whereDate('purchaseDate', '<=', $date))),
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
            'index' => ListPurchases::route('/'),
            'create' => CreatePurchase::route('/create'),
            'edit' => EditPurchase::route('/{record}/edit'),
        ];
    }
}
