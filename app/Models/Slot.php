<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slot extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    const UPDATED_AT = null;
    const HAS_PLACES = 1;
    const NO_PLACES = 2;
    const SLOT_NOT_FOUND = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'capacity',
        'remaining',
    ];

    /**
     * В одном слоте может быть много холдов
     */
    public function holds(): HasMany
    {
        return $this->hasMany(Hold::class);
    }

}
