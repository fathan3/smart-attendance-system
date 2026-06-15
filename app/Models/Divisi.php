<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    protected $table = 'divisi';

    protected $fillable = ['nama', 'deskripsi', 'acara_id'];

    public function acaraUser(): HasMany
    {
        return $this->hasMany(AcaraUser::class);
    }
}
