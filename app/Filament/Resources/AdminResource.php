<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $label = 'Admin';

    protected static ?string $slug = 'admin';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('roles', function (Builder $query) {
            $query->where('name', Role::Admin->value);
        });
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole(Role::SuperAdmin->value);
    }

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->hasRole(Role::SuperAdmin->value);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->columnSpanFull()
                    ->disableAutocomplete(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disableAutocomplete(),
                Forms\Components\TextInput::make('registration_number')
                    ->label('NIP')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disableAutocomplete(),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->disableAutocomplete(),
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->same('password')
                    ->disableAutocomplete(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAdmins::route('/'),
        ];
    }
}
