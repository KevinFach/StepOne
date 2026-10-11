<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Address;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class OrderResource extends Resource
{
    public const STATUSES = [
        'espera' => 'Espera',
        'enviado' => 'Enviado',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado',
    ];

    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'pedido';

    protected static ?string $pluralModelLabel = 'pedidos';

    protected static ?string $slug = 'pedidos';

    protected static ?string $recordTitleAttribute = 'idOrder';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->label('Estatus')
                    ->options(self::STATUSES)
                    ->default('enviado')
                    ->required(),
                DateTimePicker::make('createdAt')
                    ->label('Fecha')
                    ->default(now()),
                Select::make('idCostumer')
                    ->label('Cliente')
                    ->relationship('costumer', 'email')
                    ->searchable()
                    ->live()
                    ->required(),
                Select::make('idAddress')
                    ->label('Dirección')
                    ->options(fn (Get $get) => Address::where('idCostumer', $get('idCostumer'))
                        ->get()
                        ->mapWithKeys(fn (Address $a) => [$a->idAddres => "{$a->street} #{$a->number}, {$a->colony}"]))
                    ->required(),
                TextInput::make('shippingCost')
                    ->label('Costo de envío')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('totalAmount')
                    ->label('Total')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('shippingCompany')
                    ->label('Paquetería')
                    ->maxLength(100),
                TextInput::make('trackingNumber')
                    ->label('Número de guía')
                    ->maxLength(100),
                Select::make('idCoupon')
                    ->label('Cupón')
                    ->relationship('coupon', 'code')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('createdAt', 'desc')
            ->columns([
                TextColumn::make('idOrder')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Estatus')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'entregado' => 'success',
                        'enviado' => 'info',
                        'espera' => 'warning',
                        'cancelado' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('createdAt')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('shippingCost')
                    ->label('Envío')
                    ->money('MXN'),
                TextColumn::make('totalAmount')
                    ->label('Total')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('shippingCompany')
                    ->label('Paquetería'),
                TextColumn::make('trackingNumber')
                    ->label('Guía')
                    ->searchable(),
                TextColumn::make('address.street')
                    ->label('Dirección'),
                TextColumn::make('costumer.email')
                    ->label('Cliente'),
                TextColumn::make('coupon.code')
                    ->label('Cupón'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estatus')
                    ->options(self::STATUSES),
                Filter::make('createdAt')
                    ->label('Fecha')
                    ->schema([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['desde'], fn ($q, $date) => $q->whereDate('createdAt', '>=', $date))
                        ->when($data['hasta'], fn ($q, $date) => $q->whereDate('createdAt', '<=', $date))),
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
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
