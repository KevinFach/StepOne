<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\CreateReview;
use App\Filament\Resources\Reviews\Pages\EditReview;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Models\Review;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|UnitEnum|null $navigationGroup = 'Catálogo';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'reseña';

    protected static ?string $pluralModelLabel = 'reseñas';

    protected static ?string $slug = 'resenas';

    protected static ?string $recordTitleAttribute = 'idReview';

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
                Select::make('idCostumer')
                    ->label('Cliente')
                    ->relationship('costumer', 'email')
                    ->searchable()
                    ->required(),
                Select::make('rating')
                    ->label('Calificación')
                    ->options(array_combine(range(1, 5), range(1, 5)))
                    ->required(),
                DateTimePicker::make('createdAt')
                    ->label('Fecha')
                    ->default(now()),
                Textarea::make('comment')
                    ->label('Comentario')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idReview')
            ->columns([
                TextColumn::make('idReview')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('comment')
                    ->label('Comentario')
                    ->limit(50),
                TextColumn::make('rating')
                    ->label('Calificación')
                    ->sortable(),
                TextColumn::make('createdAt')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('sneaker.name')
                    ->label('Sneaker'),
                TextColumn::make('costumer.email')
                    ->label('Cliente'),
            ])
            ->filters([
                SelectFilter::make('rating')
                    ->label('Calificación')
                    ->options(array_combine(range(1, 5), range(1, 5))),
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
            'index' => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit' => EditReview::route('/{record}/edit'),
        ];
    }
}
