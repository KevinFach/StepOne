<?php

namespace App\Filament\Resources\Payments;

use App\Filament\Resources\Payments\Pages\CreatePayment;
use App\Filament\Resources\Payments\Pages\EditPayment;
use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Models\Payment;
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

class PaymentResource extends Resource
{
    public const METHODS = [
        'Card' => 'Tarjeta',
        'Transfer' => 'Transferencia',
        'Paypal' => 'PayPal',
    ];

    public const STATUSES = [
        'Pending' => 'Pendiente',
        'Completed' => 'Completado',
        'Failed' => 'Fallido',
    ];

    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'pago';

    protected static ?string $pluralModelLabel = 'pagos';

    protected static ?string $slug = 'pagos';

    protected static ?string $recordTitleAttribute = 'idPayment';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idOrder')
                    ->label('Pedido')
                    ->relationship('order', 'idOrder')
                    ->searchable(),
                DateTimePicker::make('payment_date')
                    ->label('Fecha de pago')
                    ->default(now()),
                TextInput::make('amount')
                    ->label('Monto')
                    ->numeric()
                    ->prefix('$'),
                Select::make('method')
                    ->label('Método')
                    ->options(self::METHODS)
                    ->default('Card'),
                Select::make('status')
                    ->label('Estado')
                    ->options(self::STATUSES)
                    ->default('Pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idPayment')
            ->columns([
                TextColumn::make('idPayment')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('idOrder')
                    ->label('Pedido')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Fecha de pago')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Monto')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('method')
                    ->label('Método')
                    ->formatStateUsing(fn (?string $state) => self::METHODS[$state] ?? $state),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => self::STATUSES[$state] ?? $state)
                    ->color(fn (?string $state) => match ($state) {
                        'Completed' => 'success',
                        'Pending' => 'warning',
                        'Failed' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('method')
                    ->label('Método')
                    ->options(self::METHODS),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(self::STATUSES),
                Filter::make('payment_date')
                    ->label('Fecha de pago')
                    ->schema([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['desde'], fn ($q, $date) => $q->whereDate('payment_date', '>=', $date))
                        ->when($data['hasta'], fn ($q, $date) => $q->whereDate('payment_date', '<=', $date))),
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
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
