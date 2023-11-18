<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $label = 'Mahasiswa';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('roles', function (Builder $query) {
            $query->where('name', Role::Student->value);
        });
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole([Role::SuperAdmin->value, Role::Admin->value]);
    }

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->hasAnyRole([Role::SuperAdmin->value, Role::Admin->value]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->placeholder('Nama Lengkap')
                    ->required(),
                Forms\Components\Select::make('study_program_id')
                    ->label('Program Studi')
                    ->searchable()
                    ->relationship('studyProgram', 'name')
                    ->preload()
                    ->required(),
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('registration_number')
                            ->label('NIM')
                            ->reactive()
                            ->placeholder('NIM')
                            ->unique(ignoreRecord: true)
                            ->afterStateUpdated(function ($state, $set) {
                                $set('email', $state . '@student.itk.ac.id');
                            })
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Email')
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->placeholder('Password')
                            ->hint('Minimal 8 karakter')
                            ->visible(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                            ->password()
                            ->minLength(8)
                            ->required(),
                        Forms\Components\Hidden::make('roles.name')
                            ->default(Role::Student->value),
                    ]),
                Forms\Components\Toggle::make('is_active')
                    ->label('Mahasiswa Aktif')
                    ->default(true),
                Forms\Components\Fieldset::make('Asal Daerah')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('province_id')
                            ->label('Provinsi')
                            ->searchable()
                            ->preload()
                            ->relationship('province', 'name')
                            ->reactive(),
                        Forms\Components\Select::make('district_id')
                            ->label('Kabupaten/Kota')
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->relationship('district', 'name', function (Builder $query, \Closure $get) {
                                $query->where('province_id', $get('province_id'));
                            }),
                        Forms\Components\Select::make('subdistrict_id')
                            ->label('Kecamatan')
                            ->searchable()
                            ->preload()
                            ->relationship('subdistrict', 'name', function (Builder $query, \Closure $get) {
                                $query->where('district_id', $get('district_id'));
                            }),
                    ]),

                Forms\Components\Section::make('Kriteria Mahasiswa')
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditUser)
                    ->schema([
                        Forms\Components\Select::make('c1_subcriteria_id')
                            ->label('C1 (IPK)')
                            ->searchable()
                            ->relationship('c1Subcriteria', 'name')
                            ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 1)->pluck('name', 'id'))
                            ->preload()
                            ->disabled(),
                        Forms\Components\Select::make('c2_subcriteria_id')
                            ->label('C2 (UKT)')
                            ->searchable()
                            ->relationship('c2Subcriteria', 'name')
                            ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 2)->pluck('name', 'id'))
                            ->preload()
                            ->disabled(),
                        Forms\Components\Select::make('c3_subcriteria_id')
                            ->label('C3 (MAHASISWA AKTIF)')
                            ->searchable()
                            ->relationship('c3Subcriteria', 'name')
                            ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 3)->pluck('name', 'id'))
                            ->preload()
                            ->disabled(),
                        Forms\Components\Select::make('c4_subcriteria_id')
                            ->label('C4 (PENGALAMAN ORGANISASI)')
                            ->searchable()
                            ->relationship('c4Subcriteria', 'name')
                            ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 4)->pluck('name', 'id'))
                            ->preload()
                            ->disabled(),
                    ]),
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
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('studyProgram.name')
                    ->label('Program Studi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area')
                    ->label('Asal Daerah')
                    ->formatStateUsing(function ($record) {
                        $html = '<ul>';
                        if(!$record->province_id && !$record->district_id && !$record->subdistrict_id) {
                            $html .= "<li> - </li>";
                        }
                        if ($record->province_id) {
                            $html .= "<li>Provinsi: {$record->province?->name} </li>";
                        }
                        if ($record->district_id) {
                            $html .= "<li>Kabupaten/Kota: {$record->district?->name} </li>";
                        }
                        if ($record->subdistrict_id) {
                            $html .= "<li>Kecamatan: {$record->subdistrict?->name} </li>";
                        }
                        $html .= '</ul>';
                        return new HtmlString($html);
                    })
                    ->wrap(),
                Tables\Columns\TextColumn::make('is_active')
                    ->label('Mahasiswa Aktif')
                    ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('transcript')
                    ->label('Transkrip Nilai')
                    ->icon(fn ($record) => $record->getFirstMediaUrl('transcript') ? 'heroicon-o-document-download' : null)
                    ->url(fn ($record) => $record->getFirstMediaUrl('transcript'))
                    ->formatStateUsing(fn ($record) => $record->getFirstMediaUrl('transcript') ? 'Download Document' : '-')
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('organizational_experience')
                    ->label('Pengalaman Organisasi')
                    ->formatStateUsing(function ($record) {
                        $html = '<ul>';
                        $record->getMedia('organizational_experience')?->each(function ($media) use (&$html) {
                            $html .= "<li><a class='text-primary-600 flex gap-1 items-center' href='{$media->getUrl()}' target='_blank'>
                                <svg class='h-4 w-4' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' aria-hidden='true'>
                                  <path stroke-linecap='round' stroke-linejoin='round' d='M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'></path>
                                </svg>
                                Download Document</a></li>";
                        }) ?? '-';
                        $html .= '</ul>';
                        return new HtmlString($html);
                    })
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('reset_password')
                        ->label('Reset Password')
                        ->color('warning')
                        ->icon('heroicon-o-key')
                        ->requiresConfirmation()
                        ->modalContent(fn ($record) => new HtmlString("Password akan direset menjadi: <strong>password</strong>"))
                        ->action(function ($record) {
                            $record->update(['password' => bcrypt('password')]);

                            Notification::make()
                                ->success()
                                ->title('Password berhasil direset')
                                ->body("Password {$record->name} telah direset menjadi <strong>password</strong>")
                                ->send();
                        }),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
