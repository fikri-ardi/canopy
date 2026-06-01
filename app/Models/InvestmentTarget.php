<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'investment_key',
        'investment_name',
        'target_amount',
    ];

    protected $casts = [
        'target_amount' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
