<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'item_id',
        'code',
        'serial_number',
        'procurement_year',
        'condition',
    ];

    public function item(){ return $this->belongsTo(Item::class); }
}
