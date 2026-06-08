<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivityDay extends Model
{
    protected $fillable = [
        'user_id',
        'active_on',
    ];

    protected function casts(): array
    {
        return [
            'active_on' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
