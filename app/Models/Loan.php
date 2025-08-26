<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    //
    protected $fillable = ['code','partner_id','loan_date','purpose','user_id','status'];
    protected static function booted(){
        static::creating(function($loan){
            $loan->code = $loan->code ?: 'LOAN-'.now()->format('Y').'-'.str_pad((static::max('id')+1) ?? 1,4,'0',STR_PAD_LEFT);
        });
    }
    public function partner(){ return $this->belongsTo(Partner::class); }
    public function items(){ return $this->hasMany(LoanItem::class); }
}
