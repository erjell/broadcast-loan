<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'barcode',
        'name',
        'serial_number',
        'procurement_year',
        'details',
        'category_id',
        'stock',
        'condition',
    ];

    public function category(){ return $this->belongsTo(Category::class); }
    public function loanItems(){ return $this->hasMany(LoanItem::class); }
}
