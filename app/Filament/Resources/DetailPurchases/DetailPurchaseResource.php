<?php

namespace App\Filament\Resources\DetailPurchases;

use App\Filament\Resources\DetailPurchases\Pages\CreateDetailPurchase;
use App\Filament\Resources\DetailPurchases\Pages\EditDetailPurchase;
use App\Filament\Resources\DetailPurchases\Pages\ListDetailPurchases;
use App\Filament\Resources\SneakerVariants\SneakerVariantResource;
use App\Models\DetailPurchase;
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

class DetailPurchaseResource extends Resource
{
    protected static ?string $model = DetailPurchase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Inventario';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'detalle de compra';

    protected static ?string $pluralModelLabel = 'detalles de compra';

    protected static ?string $slug = 'detalles-compra';

    protected static ?string $recordTitleAttribute = 'idDetailPurchase';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idPurchase')
                    ->label('Compra')
                    ->relationship('purchase', 'invoiceNumber')
                    ->searchable()
                    ->preload()
                    ->required(),
                SneakerVariantResource::variantSelect(),
                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required()
                    ->integer()
                    ->minValue(1),
                TextInput::make('unitCost')
                    ->label('Costo unitario')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idDetailPurchase')
            ->columns([
                TextColumn::make('idDetailPurchase')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->searchable(),
                TextColumn::make('unitCost')
                    ->label('Costo unitario')
                    ->money('MXN')
                    ->searchable(),
                TextColumn::make('variant.label')
                    ->label('Variante'),
                TextColumn::make('purchase.invoiceNumber')
                    ->label('Compra'),
            ])
            ->filters([
                SelectFilter::make('idSneakerVariant')
                    ->label('Variante')
                    ->relationship('variant', 'color')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->label),
                SelectFilter::make('idPurchase')
                    ->label('Compra')
                    ->relationship('purchase', 'invoiceNumber'),
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
            'index' => ListDetailPurchases::route('/'),
            'create' => CreateDetailPurchase::route('/create'),
            'edit' => EditDetailPurchase::route('/{record}/edit'),
        ];
    }
}
