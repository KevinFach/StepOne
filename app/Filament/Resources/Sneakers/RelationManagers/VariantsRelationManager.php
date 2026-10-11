<?php

namespace App\Filament\Resources\Sneakers\RelationManagers;

use App\Filament\Resources\SneakerVariants\SneakerVariantResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

/**
 * Equivalente al SneakerVariantInline de Unfold.
 */
class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Variantes';

    protected static ?string $modelLabel = 'variante';

    public function form(Schema $schema): Schema
    {
        return $schema->components(SneakerVariantResource::variantFields());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('color')
            ->defaultSort('size')
            ->columns(SneakerVariantResource::variantColumns())
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
