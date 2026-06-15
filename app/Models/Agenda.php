<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// app/Models/Agenda.php
class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = [
        'nama',
        'acara_id',
        'checkin',
        'batas_checkin',
        'checkout',
        'batas_checkout',
    ];

    protected $casts = [
        'checkin' => 'datetime',
        'batas_checkin' => 'datetime',
        'checkout' => 'datetime',
        'batas_checkout' => 'datetime',
    ];

    public function acara(): BelongsTo
    {
        return $this->belongsTo(Acara::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}
