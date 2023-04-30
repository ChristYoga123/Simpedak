<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOwner extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

    public function ProductTypes()
    {
        return $this->hasMany(ProductType::class);
    }

    public function ProductPrice()
    {
        return $this->hasOne(ProductPrice::class);
    }
}
