<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $fillable = ['barcode','name','category_id','stock','condition','notes'];
    public function category(){ return $this->belongsTo(Category::class); }
    public function loanItems(){ return $this->hasMany(LoanItem::class); }
}
