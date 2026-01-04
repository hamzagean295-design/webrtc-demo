<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiNote extends Model
{
    protected $fillable = [
        'consultation_id',
        'content', // json
        'validated_by_doctor'
    ];

    protected $casts = [
        'validated_by_doctor' => 'boolean',
        'content' => 'array'
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }
}
