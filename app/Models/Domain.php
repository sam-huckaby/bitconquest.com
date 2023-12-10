<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostname',
        'tld',
        'score',
        'flair',
        'verified',
    ];

    public function team(): BelongsTo {
        return $this->belongsTo(Team::class);
    }
}
