<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Scholarship extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $guarded = ['id'];

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
}
