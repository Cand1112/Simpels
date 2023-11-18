<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgram extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function students(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scholarships(): BelongsToMany
    {
        return $this->belongsToMany(ScholarshipStudyProgram::class, ScholarshipStudyProgram::class);
    }
}
