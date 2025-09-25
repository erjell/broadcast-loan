<?php

namespace App\Models;

use App\Models\Category;
use App\Models\LoanItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    protected $fillable = [
        'name',
        'details',
        'category_id',
        'serial_number',
        'procurement_year',
        'condition',
        'is_missing',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function loanItems(): HasMany
    {
        return $this->hasMany(LoanItem::class);
    }

    public function activeLoanItem(): HasOne
    {
        return $this->hasOne(LoanItem::class)
            ->whereNull('return_condition')
            ->whereHas('loan', function($q){
                $q->whereIn('status', ['dipinjam','sebagian_kembali']);
            });
    }

    /**
     * Latest loan-item record that already has a return condition (with notes).
     * Used to display the last return notes as tooltip on the item condition badge.
     */
    public function lastReturn(): HasOne
    {
        return $this->hasOne(LoanItem::class)
            ->whereNotNull('return_condition')
            ->latestOfMany();
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
