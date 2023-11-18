<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'study_program_id',
        'province_id',
        'district_id',
        'subdistrict_id',
        'registration_number',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->remember_token = Str::random(10);

            if (Str::contains($user->email, '@student.itk.ac.id')) {
                $user->assignRole(Role::Student->value);
            }

            return $user;
        });
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function subdistrict(): BelongsTo
    {
        return $this->belongsTo(Subdistrict::class);
    }

    public function c1Subcriteria(): BelongsTo
    {
        return $this->belongsTo(Subcriteria::class, 'c1_subcriteria_id');
    }

    public function c2Subcriteria(): BelongsTo
    {
        return $this->belongsTo(Subcriteria::class, 'c2_subcriteria_id');
    }

    public function c3Subcriteria(): BelongsTo
    {
        return $this->belongsTo(Subcriteria::class, 'c3_subcriteria_id');
    }

    public function c4Subcriteria(): BelongsTo
    {
        return $this->belongsTo(Subcriteria::class, 'c4_subcriteria_id');
    }

    public function hasCompletePersonalData(): bool
    {
        return $this->c1_subcriteria_id !== null
            && $this->c2_subcriteria_id !== null
            && $this->c3_subcriteria_id !== null
            && $this->c4_subcriteria_id !== null;
    }
}
