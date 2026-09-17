<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialGoalMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'financial_goal_id',
        'type',
        'amount',
        'note',
        'occurred_at',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
