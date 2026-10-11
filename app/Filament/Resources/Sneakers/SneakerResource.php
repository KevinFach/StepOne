<?php

namespace App\Filament\Resources\Sneakers;

use App\Filament\Resources\Sneakers\Pages\CreateSneaker;
use App\Filament\Resources\Sneakers\Pages\EditSneaker;
use App\Filament\Resources\Sneakers\Pages\ListSneakers;
use App\Filament\Resources\Sneakers\RelationManagers\VariantsRelationManager;
use App\Models\Sneaker;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class SneakerResource extends Resource
{
    public const ESTADOS = [
        Sneaker::ACTIVE => 'Activo',
        Sneaker::INACTIVE => 'Inactivo',
    ];

    protected static ?string $model = Sneaker::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|UnitEnum|null $navigationGroup = 'Catálogo';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'sneaker';

    protected static ?string $pluralModelLabel = 'sneakers';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(150),
                Select::make('estado')
                    ->options(self::ESTADOS)
                    ->default(Sneaker::ACTIVE)
                    ->required(),
                Select::make('idBrand')
                    ->label('Marca')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('idCategory')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Imagen principal')
                    ->image()
                    ->disk('public')
                    ->directory('sneakers')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idSneaker')
            ->columns([
                TextColumn::make('idSneaker')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->label('Marca')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable(),
                TextColumn::make('estado')
                    ->badge()
                    ->formatStateUsing(fn (int $state) => self::ESTADOS[$state] ?? $state)
                    ->color(fn (int $state) => $state === Sneaker::ACTIVE ? 'success' : 'gray'),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options(self::ESTADOS),
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
            VariantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSneakers::route('/'),
            'create' => CreateSneaker::route('/create'),
            'edit' => EditSneaker::route('/{record}/edit'),
        ];
    }
}
