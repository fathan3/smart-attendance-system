<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

// app/Models/Acara.php
class Acara extends Model
{
    protected $table = 'acara';

    protected $fillable = [
        'nama', 'deskripsi', 'tanggal_mulai',
        'tanggal_selesai', 'lokasi', 'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Many-to-many ke users, pivot menyimpan divisi_id
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'acara_user')
            ->withPivot('divisi_id')
            ->withTimestamps();
    }

    public function agenda(): HasMany
    {
        return $this->hasMany(Agenda::class);
    }
}
