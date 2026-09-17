<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'position',
        'target_amount',
        'target_date',
    ];

    public function movements()
    {
        return $this->hasMany(FinancialGoalMovement::class);
    }
}
