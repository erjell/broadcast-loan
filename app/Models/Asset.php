<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Item;

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

    protected static function booted(): void
    {
        static::creating(function (Asset $asset) {
            if (!$asset->code) {
                $item = Item::find($asset->item_id);
                $count = $item->assets()->count() + 1;
                $asset->code = $item->code . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
