<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    protected $fillable = ['name', 'income'];

    use HasFactory;

    public function spends(): HasMany
    {
        return $this->hasMany(Spend::class);
    }
}
