<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebService extends Model
{
    use HasFactory;
    protected $casts = [
        'token' => 'json'
    ];

    protected $fillable = [
        'user_id',
        'name',
        'token'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
