<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    public function Transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function ProductOwner()
    {
        return $this->belongsTo(ProductOwner::class);
    }
}
