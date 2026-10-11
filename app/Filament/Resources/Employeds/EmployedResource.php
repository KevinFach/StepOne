<?php

namespace App\Filament\Resources\Employeds;

use App\Filament\Resources\Employeds\Pages\CreateEmployed;
use App\Filament\Resources\Employeds\Pages\EditEmployed;
use App\Filament\Resources\Employeds\Pages\ListEmployeds;
use App\Models\Employed;
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

class EmployedResource extends Resource
{
    public const STATUSES = [
        'active' => 'Activo',
        'inactive' => 'Inactivo',
    ];

    protected static ?string $model = Employed::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'empleado';

    protected static ?string $pluralModelLabel = 'empleados';

    protected static ?string $slug = 'empleados';

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('last_name')
                    ->label('Apellido')
                    ->required()
                    ->maxLength(100),
                TextInput::make('email')
                    ->label('Correo')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20),
                Select::make('status')
                    ->label('Estado')
                    ->options(self::STATUSES)
                    ->default('active')
                    ->required(),
                TextInput::make('RFC')
                    ->label('RFC')
                    ->maxLength(13),
                TextInput::make('position')
                    ->label('Puesto')
                    ->maxLength(100),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->helperText(fn (string $operation) => $operation === 'edit' ? 'Déjala vacía para no cambiarla.' : null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idEmployed')
            ->columns([
                TextColumn::make('idEmployed')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('first_name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Apellido')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => $state === 'active' ? 'success' : 'gray'),
                TextColumn::make('RFC')
                    ->label('RFC')
                    ->searchable(),
                TextColumn::make('position')
                    ->label('Puesto'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(self::STATUSES),
                SelectFilter::make('position')
                    ->label('Puesto')
                    ->options(fn () => Employed::whereNotNull('position')->distinct()->orderBy('position')->pluck('position', 'position')->all()),
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
            'index' => ListEmployeds::route('/'),
            'create' => CreateEmployed::route('/create'),
            'edit' => EditEmployed::route('/{record}/edit'),
        ];
    }
}
