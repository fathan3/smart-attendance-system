<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// app/Models/Absensi.php
class Absensi extends Model
{
    protected $table = 'absensi';
    protected $fillable = [
        'agenda_id', 'user_id', 'rfid_uid',
        'waktu_masuk', 'waktu_pulang',
        'status', 'keterangan'
    ];

    protected $casts = [
        'waktu_masuk'  => 'datetime',
        'waktu_pulang' => 'datetime',
    ];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
