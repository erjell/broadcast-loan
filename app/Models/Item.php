<?php

namespace App\Models;

use App\Models\{Asset, Category};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Item extends Model
{
    protected $fillable = [
        'name',
        'details',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    protected static function booted(): void
    {
            static::creating(function (Item $item) {
                $category = Category::find($item->category_id);
                $prefix = strtoupper($category->code);
                $count = static::where('category_id', $item->category_id)->count() + 1;
                $item->code = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
            });
    }
}
