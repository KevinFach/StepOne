<?php

namespace App\Filament\Resources\SneakerVariants;

use App\Filament\Resources\SneakerVariants\Pages\CreateSneakerVariant;
use App\Filament\Resources\SneakerVariants\Pages\EditSneakerVariant;
use App\Filament\Resources\SneakerVariants\Pages\ListSneakerVariants;
use App\Models\SneakerVariant;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class SneakerVariantResource extends Resource
{
    public const GENDERS = [
        'Man' => 'Hombre',
        'Woman' => 'Mujer',
        'Unisex' => 'Unisex',
    ];

    protected static ?string $model = SneakerVariant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|UnitEnum|null $navigationGroup = 'Catálogo';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'variante';

    protected static ?string $pluralModelLabel = 'variantes';

    protected static ?string $slug = 'variantes';

    protected static ?string $recordTitleAttribute = 'color';

    /**
     * Campos de la variante, compartidos con el relation manager de Sneaker.
     */
    public static function variantFields(): array
    {
        return [
            TextInput::make('size')
                ->label('Talla')
                ->required()
                ->maxLength(10),
            Select::make('gender')
                ->label('Género')
                ->options(self::GENDERS)
                ->default('Unisex')
                ->required(),
            TextInput::make('color')
                ->required()
                ->maxLength(50),
            TextInput::make('price')
                ->label('Precio')
                ->required()
                ->numeric()
                ->prefix('$'),
            TextInput::make('stock')
                ->numeric()
                ->default(0),
            FileUpload::make('image')
                ->label('Imagen de la variante (opcional)')
                ->image()
                ->disk('public')
                ->directory('sneakers/variants'),
        ];
    }

    /**
     * Selector de variante para los detalles de carrito, pedido y compra.
     */
    public static function variantSelect(): Select
    {
        return Select::make('idSneakerVariant')
            ->label('Variante')
            ->relationship('variant', 'color', fn ($query) => $query->with('sneaker'))
            ->getOptionLabelFromRecordUsing(fn (SneakerVariant $record) => $record->label)
            ->searchable()
            ->preload()
            ->required();
    }

    /**
     * Columnas de la variante, compartidas con el relation manager de Sneaker.
     */
    public static function variantColumns(): array
    {
        return [
            TextColumn::make('idSneakerVariant')
                ->label('ID')
                ->sortable()
                ->searchable(),
            ImageColumn::make('preview')
                ->label('Vista previa')
                ->state(fn (SneakerVariant $record) => $record->getImage()),
            TextColumn::make('size')
                ->label('Talla')
                ->sortable(),
            TextColumn::make('gender')
                ->label('Género')
                ->formatStateUsing(fn (string $state) => self::GENDERS[$state] ?? $state),
            TextColumn::make('color'),
            TextColumn::make('price')
                ->label('Precio')
                ->money('MXN')
                ->sortable(),
            TextColumn::make('stock')
                ->sortable(),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idSneaker')
                    ->label('Sneaker')
                    ->relationship('sneaker', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                ...self::variantFields(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idSneakerVariant')
            ->columns([
                ...self::variantColumns(),
                TextColumn::make('sneaker.name')
                    ->label('Sneaker')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('size')
                    ->label('Talla')
                    ->options(fn () => SneakerVariant::distinct()->orderBy('size')->pluck('size', 'size')->all()),
                SelectFilter::make('gender')
                    ->label('Género')
                    ->options(self::GENDERS),
                SelectFilter::make('color')
                    ->options(fn () => SneakerVariant::distinct()->orderBy('color')->pluck('color', 'color')->all()),
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
            'index' => ListSneakerVariants::route('/'),
            'create' => CreateSneakerVariant::route('/create'),
            'edit' => EditSneakerVariant::route('/{record}/edit'),
        ];
    }
}
