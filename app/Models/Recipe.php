<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function ProductOwner()
    {
        return $this->belongsTo(ProductOwner::class);
    }

    public function RawProduct()
    {
        return $this->belongsTo(Product::class);
    }
}
