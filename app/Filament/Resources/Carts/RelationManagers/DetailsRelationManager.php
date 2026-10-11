<?php

namespace App\Filament\Resources\Carts\RelationManagers;

use App\Filament\Resources\SneakerVariants\SneakerVariantResource;
use App\Models\SneakerVariant;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Equivalente al DetailCartInline de Unfold: el precio se toma de la variante al agregarla.
 */
class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    protected static ?string $title = 'Productos';

    protected static ?string $modelLabel = 'producto';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            SneakerVariantResource::variantSelect(),
            TextInput::make('quantity')
                ->label('Cantidad')
                ->required()
                ->integer()
                ->minValue(1)
                ->default(1),
            TextInput::make('priceAtAddition')
                ->label('Precio al agregar')
                ->prefix('$')
                ->disabled()
                ->dehydrated(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('idDetailCart')
            ->columns([
                TextColumn::make('variant.label')
                    ->label('Variante'),
                TextColumn::make('quantity')
                    ->label('Cantidad'),
                TextColumn::make('priceAtAddition')
                    ->label('Precio al agregar')
                    ->money('MXN'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['priceAtAddition'] = SneakerVariant::find($data['idSneakerVariant'])?->price;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
