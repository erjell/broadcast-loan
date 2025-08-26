<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'details',
        'category_id',
        'stock',
    ];

    public function category(){ return $this->belongsTo(Category::class); }
    public function assets(){ return $this->hasMany(Asset::class); }
    public function loanItems(){ return $this->hasMany(LoanItem::class); }
}
