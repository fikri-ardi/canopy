<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function balance(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->movements->sum(function ($movement) {
                    return $movement->type === 'in'
                        ? $movement->amount
                        : -$movement->amount;
                });
            },
        );
    }

    protected function progress(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->target_amount > 0
                ? Number::percentage($this->balance / $this->target_amount * 100)
                : 0,
        );
    }
}
