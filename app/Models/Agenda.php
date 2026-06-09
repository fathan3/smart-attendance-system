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
        'acara_id', 'nama',
        'jam_mulai', 'jam_selesai', 'batas_absen_masuk'
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