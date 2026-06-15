<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

// app/Models/AcaraUser.php
class AcaraUser extends Pivot
{
    protected $table = 'acara_user';

    protected $fillable = ['acara_id', 'user_id', 'divisi_id'];

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }
}
