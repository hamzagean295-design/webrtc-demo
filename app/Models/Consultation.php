<?php

namespace App\Models;

use App\Enums\ConsultationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'status',
        'scheduled_at'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'status' => ConsultationStatus::class
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function audioRecord(): HasOne
    {
        return $this->hasOne(AudioRecord::class);
    }

    public function aiNote(): HasOne
    {
        return $this->hasOne(AiNote::class);
    }
}
