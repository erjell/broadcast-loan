<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'name',
        'details',
        'category_id',
        'serial_number',
        'procurement_year',
        'condition',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Item $item) {
            $category = Category::find($item->category_id);
            $prefix = strtoupper($category->code_category);
            $count = static::where('category_id', $item->category_id)->count() + 1;
            $item->code = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
        });
    }
}
