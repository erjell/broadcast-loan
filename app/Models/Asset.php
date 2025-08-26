<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'code',
        'item_id',
        'serial_number',
        'procurement_year',
        'condition',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
