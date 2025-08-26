<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Item extends Model
{
    protected $fillable = [
        'name',
        'details',
        'category_id',
        'stock',
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            $category = Category::findOrFail($item->category_id);
            $prefix = $category->prefix;
            $last = static::whereHas('category', fn($q) => $q->where('id', $category->id))
                ->where('code', 'like', $prefix.'%')
                ->max('code');
            $number = $last ? intval(substr($last, strlen($prefix))) + 1 : 1;
            $item->code = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }

    public function category(){ return $this->belongsTo(Category::class); }
    public function assets(){ return $this->hasMany(Asset::class); }
    public function loanItems(){ return $this->hasMany(LoanItem::class); }
}
