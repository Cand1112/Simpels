<?php

namespace App\Filament\Pages;

use App\Enums\Role;
use App\Models\User;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PersonalData extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.personal-data';

    protected static ?string $navigationLabel = 'Data Pribadi';

    protected static ?string $title = 'Data Pribadi';


    public User $mahasiswa;

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole(Role::Student->value);
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole(Role::Student->value), 403);
        $this->mahasiswa = auth()->user();

        $this->form->fill([
            'name' => $this->mahasiswa->name,
            'study_program_id' => $this->mahasiswa->study_program_id,
            'registration_number' => $this->mahasiswa->registration_number,
            'email' => $this->mahasiswa->email,
            'province_id' => $this->mahasiswa->province_id,
            'district_id' => $this->mahasiswa->district_id,
            'subdistrict_id' => $this->mahasiswa->subdistrict_id,
            'roles.name' => $this->mahasiswa->roles->first()->name,
            'c1_subcriteria_id' => $this->mahasiswa->c1_subcriteria_id,
            'c2_subcriteria_id' => $this->mahasiswa->c2_subcriteria_id,
            'c3_subcriteria_id' => $this->mahasiswa->c3_subcriteria_id,
            'c4_subcriteria_id' => $this->mahasiswa->c4_subcriteria_id,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->placeholder('Nama Lengkap')
                        ->disabled()
                        ->required(),
                    Select::make('study_program_id')
                        ->label('Program Studi')
                        ->searchable()
                        ->disabled()
                        ->relationship('studyProgram', 'name')
                        ->preload()
                        ->required(),
                ]),
            Grid::make(2)
                ->schema([
                    TextInput::make('registration_number')
                        ->label('NIM')
                        ->reactive()
                        ->disabled()
                        ->placeholder('NIM')
                        ->unique(ignoreRecord: true)
                        ->afterStateUpdated(function ($state, $set) {
                            $set('email', $state . '@student.itk.ac.id');
                        })
                        ->required(),
                    TextInput::make('email')
                        ->label('Email')
                        ->placeholder('Email')
                        ->unique(ignoreRecord: true)
                        ->disabled()
                        ->required(),
                    Hidden::make('roles.name')
                        ->default(Role::Student->value),
                ]),
            Fieldset::make('Asal Daerah')
                ->columns(3)
                ->schema([
                    Select::make('province_id')
                        ->label('Provinsi')
                        ->searchable()
                        ->preload()
                        ->relationship('province', 'name')
                        ->reactive(),
                    Select::make('district_id')
                        ->label('Kabupaten/Kota')
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->relationship('district', 'name', function (Builder $query, \Closure $get) {
                            $query->where('province_id', $get('province_id'));
                        }),
                    Select::make('subdistrict_id')
                        ->label('Kecamatan')
                        ->searchable()
                        ->preload()
                        ->relationship('subdistrict', 'name', function (Builder $query, \Closure $get) {
                            $query->where('district_id', $get('district_id'));
                        }),
                ]),

            Section::make('Kriteria Mahasiswa')
                ->schema([
                    Select::make('c1_subcriteria_id')
                        ->label('C1 (IPK)')
                        ->searchable()
                        ->relationship('c1Subcriteria', 'name')
                        ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 1)->pluck('name', 'id'))
                        ->preload()
                ->required(),
                    Select::make('c2_subcriteria_id')
                        ->label('C2 (UKT)')
                        ->searchable()
                        ->relationship('c2Subcriteria', 'name')
                        ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 2)->pluck('name', 'id'))
                        ->preload()
                        ->required(),
                    Select::make('c3_subcriteria_id')
                        ->label('C3 (MAHASISWA AKTIF)')
                        ->searchable()
                        ->relationship('c3Subcriteria', 'name')
                        ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 3)->pluck('name', 'id'))
                        ->preload()
                        ->required(),
                    Select::make('c4_subcriteria_id')
                        ->label('C4 (PENGALAMAN ORGANISASI)')
                        ->searchable()
                        ->relationship('c4Subcriteria', 'name')
                        ->options(fn () => \App\Models\Subcriteria::where('criteria_id', 4)->pluck('name', 'id'))
                        ->preload()
                        ->required(),
                ]),

            Section::make('Upload Dokumen')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('transcript')
                        ->label('Transkrip Nilai')
                        ->acceptedFileTypes(['application/pdf'])
                        ->rules('file')
                        ->enableDownload()
                        ->collection('transcript'),
                    SpatieMediaLibraryFileUpload::make('organizational_experience')
                        ->label('Surat Keterangan Pengalaman Organisasi')
                        ->acceptedFileTypes(['application/pdf'])
                        ->rules('file')
                        ->enableDownload()
                        ->multiple()
                        ->collection('organizational_experience'),
                ]),
        ];
    }

    protected function getFormModel(): Model|string|null
    {
        return $this->mahasiswa;
    }

    public function save(): void
    {
        $this->mahasiswa->update(
            $this->form->getState(),
        );

        Notification::make()
            ->title('Data Pribadi')
            ->body('Data pribadi berhasil diperbarui.')
            ->success()
            ->send();
    }
}
