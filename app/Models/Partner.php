<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    //
    protected $fillable = ['name','unit','phone'];
    public function loans(){ return $this->hasMany(Loan::class); }
}
