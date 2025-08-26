<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','code'];

    public function items(){ return $this->hasMany(Item::class); }

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            $category->code = strtoupper($category->code);
        });
    }
}
