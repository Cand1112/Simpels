<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CriteriaResource\Pages;
use App\Filament\Resources\CriteriaResource\RelationManagers;
use App\Models\Criteria;
use App\Models\Subcriteria;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class CriteriaResource extends Resource
{
    protected static ?string $model = Criteria::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $label = 'Kriteria';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('weight')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Kode')
                    ->formatStateUsing(function ($state) {
                        return "C$state";
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Bobot Kriteria')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subcriterias')
                    ->label('Subkriteria')
                    ->formatStateUsing(function ($state) {
                        return new HtmlString(
                            collect($state)
                                ->map(fn ($subcriteria) => "<span class='badge badge-primary'>$subcriteria->name</span>")
                                ->join('<br>')
                        );
                    }),
                Tables\Columns\TextColumn::make('subcriterias_weight')
                    ->label('Bobot Subkriteria')
                    ->formatStateUsing(function ($record) {
                        return new HtmlString(
                            collect($record->subcriterias()->get())
                                ->map(fn ($subcriteria) => "<span class='badge badge-primary'>$subcriteria->weight</span>")
                                ->join('<br>')
                        );
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Edit Bobot Kriteria')
                        ->icon('heroicon-o-pencil')
                        ->form([
                            Forms\Components\TextInput::make('weight')
                                ->label('Bobot Kriteria')
                                ->numeric()
                                ->required(),
                        ]),

                    Tables\Actions\Action::make('edit_subcriteria')
                        ->label('Edit Bobot Subkriteria')
                        ->icon('heroicon-o-pencil')
                        ->form(function ($record) {
                            $forms = [];
                            $record->subcriterias()->each(function ($subcriteria) use (&$forms) {
                                $forms[] = Forms\Components\TextInput::make("$subcriteria->id")
                                    ->label($subcriteria->name)
                                    ->numeric()
                                    ->default($subcriteria->weight)
                                    ->required();
                            });
                            return $forms;
                        })
                        ->action(function ($data) {
                            foreach ($data as $key => $value) {
                                Subcriteria::find($key)->update(['weight' => $value]);
                            }

                            Notification::make()
                                ->body('Berhasil mengubah bobot subkriteria')
                                ->success()
                                ->send();
                        })
                ])
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCriterias::route('/'),
        ];
    }
}
