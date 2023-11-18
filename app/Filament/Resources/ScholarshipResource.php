<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\ScholarshipResource\Pages;
use App\Filament\Resources\ScholarshipResource\RelationManagers;
use App\Models\Scholarship;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ScholarshipResource extends Resource
{
    protected static ?string $model = Scholarship::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $label = 'Beasiswa';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\Fieldset::make('Khusus Daerah')
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

                Forms\Components\Section::make('Kriteria Minimal')
                    ->schema([
                        Forms\Components\Select::make('c1_subcriteria_id')
                            ->label('C1 (IPK)')
                            ->searchable()
                            ->relationship('c1Subcriteria', 'name')
                            ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 1)->pluck('name', 'id'))
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('c2_subcriteria_id')
                            ->label('C2 (UKT)')
                            ->searchable()
                            ->relationship('c2Subcriteria', 'name')
                            ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 2)->pluck('name', 'id'))
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('c3_subcriteria_id')
                            ->label('C3 (MAHASISWA AKTIF)')
                            ->searchable()
                            ->relationship('c3Subcriteria', 'name')
                            ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 3)->pluck('name', 'id'))
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('c4_subcriteria_id')
                            ->label('C4 (PENGALAMAN ORGANISASI)')
                            ->searchable()
                            ->relationship('c4Subcriteria', 'name')
                            ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 4)->pluck('name', 'id'))
                            ->preload()
                            ->required(),
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
                Tables\Columns\TextColumn::make('area')
                    ->label('Daerah Khusus')
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
                Tables\Columns\TextColumn::make('c1Subcriteria.name')
                    ->label('C1 (IPK)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('c2Subcriteria.name')
                    ->label('C2 (UKT)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('c3Subcriteria.name')
                    ->label('C3 (MAHASISWA AKTIF)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('c4Subcriteria.name')
                    ->label('C4 (PENGALAMAN ORGANISASI)')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasAnyRole([Role::Admin->value, Role::SuperAdmin->value])),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()->hasAnyRole([Role::Admin->value, Role::SuperAdmin->value])),
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
            'index' => Pages\ListScholarships::route('/'),
            'create' => Pages\CreateScholarship::route('/create'),
            'edit' => Pages\EditScholarship::route('/{record}/edit'),
        ];
    }
}
