<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'investment_key',
        'investment_name',
        'type',
        'amount',
        'occurred_on',
        'note',
    ];

    protected $casts = [
        'amount' => 'integer',
        'occurred_on' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
