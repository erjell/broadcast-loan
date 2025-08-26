<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{
    //
    protected $fillable = ['loan_id','item_id','qty','returned_qty','return_condition','return_notes'];
    public function loan(){ return $this->belongsTo(Loan::class); }
    public function item(){ return $this->belongsTo(Item::class); }
    public function getRemainingAttribute(){ return max(0, $this->qty - $this->returned_qty); }
}
