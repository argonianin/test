<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hold extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    const UPDATED_AT = null;
    const STATUS_CONFIRMED = 1;
    const STATUS_HELD = 2;
    const STATUS_CANCELLED = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'slot_id',
        'status',
        'created_at',
    ];

    /**
     * Много холдов принадлежит одному слоту
     */
    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }

}
