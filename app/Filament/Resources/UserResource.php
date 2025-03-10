<?php
namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use Filament\Facades\Filament;

class UserResource extends Resource
{
    protected static ?string $model           = User::class;
    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationLabel = "Gebruikers";
    protected static ?string $navigationGroup = 'Beheer';

    public static function form(Form $form): Form
    {
        
        return $form->schema([
            TextInput::make('name')
                ->label('Naam')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('E-mail')
                ->email()
                ->required()
                ->unique(User::class, 'email'),

            TextInput::make('password')
                ->label('Wachtwoord')
                ->password()
                ->required()
                ->minLength(8),

            Select::make('roles')
                ->relationship('roles', 'name')
                ->saveRelationshipsUsing(function ($record, $state) {
                    $record->roles()->syncWithPivotValues($state, [config('permission.column_names.team_foreign_key') => getPermissionsTeamId()]);
                })
                ->multiple()
                ->preload()
                ->searchable(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            TextColumn::make('name')
                ->label('Naam')
                ->searchable()
                ->sortable(),

            TextColumn::make('email')
                ->label('E-mail')
                ->searchable()
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Aangemaakt op')
                ->dateTime('d-m-Y H:i')
                ->sortable(),
        ])
            ->filters([
                Filter::make('recent')
                    ->label('Nieuwste eerst')
                    ->query(fn(Builder $query) => $query->latest()),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Snel bewerken')
                    ->tooltip('Bewerken')
                    ->label('')
                    ->modalIcon('heroicon-o-pencil')
                    ->slideOver(),
                DeleteAction::make()
                    ->modalIcon('heroicon-o-trash')
                    ->tooltip('Verwijderen')
                    ->label('')
                    ->modalHeading('Verwijderen')
                    ->color('danger'),
                Impersonate::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            // 'create' => CreateUser::route('/create'),
            // 'edit'   => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return "Gebruikers";
    }

}
