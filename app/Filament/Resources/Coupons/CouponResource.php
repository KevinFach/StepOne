<?php

namespace App\Filament\Resources\Coupons;

use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use App\Models\Coupon;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CouponResource extends Resource
{
    public const STATUSES = [
        'active' => 'Activo',
        'inactive' => 'Inactivo',
    ];

    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|UnitEnum|null $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'cupón';

    protected static ?string $pluralModelLabel = 'cupones';

    protected static ?string $slug = 'cupones';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->maxLength(11)
                    ->unique(ignoreRecord: true),
                Select::make('status')
                    ->label('Estado')
                    ->options(self::STATUSES)
                    ->default('active')
                    ->required(),
                TextInput::make('discountValue')
                    ->label('Descuento')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('maxUses')
                    ->label('Usos máximos')
                    ->required()
                    ->integer()
                    ->minValue(0),
                DatePicker::make('validFrom')
                    ->label('Válido desde')
                    ->required(),
                DatePicker::make('validUntil')
                    ->label('Válido hasta')
                    ->required()
                    ->afterOrEqual('validFrom'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idCoupon')
            ->columns([
                TextColumn::make('idCoupon')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('maxUses')
                    ->label('Usos máximos'),
                TextColumn::make('discountValue')
                    ->label('Descuento')
                    ->money('MXN'),
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => $state === 'active' ? 'success' : 'gray'),
                TextColumn::make('validFrom')
                    ->label('Válido desde')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('validUntil')
                    ->label('Válido hasta')
                    ->date('d/m/Y')
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

    public static function getPages(): array
    {
        return [
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
