<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'chest', 'length'];

    protected $casts = [
        'chest' => 'float',
        'length' => 'float',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
