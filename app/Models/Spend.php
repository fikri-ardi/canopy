<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

class Spend extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Interact with the user's first name.
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Number::format((int)$value, locale: 'id'),
            set: fn(string $value) => str_replace(".", "", $value),
        );
    }
}
