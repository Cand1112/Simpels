<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subcriteria extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }

    public function scholarshipSubcriteria(): BelongsToMany
    {
        return $this->belongsToMany(ScholarshipSubcriteria::class);
    }

    public function studentSubcriteria(): BelongsToMany
    {
        return $this->belongsToMany(StudentSubcriteria::class);
    }
}
