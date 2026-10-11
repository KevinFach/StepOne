<?php

namespace App\Filament\Resources\Clients;

use App\Filament\Resources\Clients\Pages\CreateClient;
use App\Filament\Resources\Clients\Pages\EditClient;
use App\Filament\Resources\Clients\Pages\ListClients;
use App\Models\Costumer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

/**
 * Costumer con is_admin = false (ClientProxy en Django).
 */
class ClientResource extends Resource
{
    protected static ?string $model = Costumer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Usuarios';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'cliente';

    protected static ?string $pluralModelLabel = 'clientes';

    protected static ?string $slug = 'clientes';

    protected static ?string $recordTitleAttribute = 'email';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->clients();
    }

    /**
     * Formulario de Costumer, compartido con AdministratorResource.
     */
    public static function costumerForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información personal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label('Correo')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->helperText(fn (string $operation) => $operation === 'edit' ? 'Déjala vacía para no cambiarla.' : null),
                        TextInput::make('first_name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('last_name')
                            ->label('Apellido')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),
                    ]),
                Section::make('Datos fiscales')
                    ->columns(2)
                    ->schema([
                        TextInput::make('rfc')
                            ->label('RFC')
                            ->required()
                            ->length(13)
                            ->unique(ignoreRecord: true),
                        TextInput::make('CURP')
                            ->label('CURP')
                            ->required()
                            ->length(18)
                            ->unique(ignoreRecord: true),
                    ]),
                Section::make('Estado')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return static::costumerForm($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('idCostumer')
            ->columns([
                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('first_name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_name')
                    ->label('Apellido')
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Teléfono'),
                TextColumn::make('rfc')
                    ->label('RFC')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
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
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }
}
