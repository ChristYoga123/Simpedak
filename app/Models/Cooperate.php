<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Cooperate extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ["id"];

    public function Owner()
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function Supplier()
    {
        return $this->belongsTo(User::class, "supplier_id");
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("cooperate-letter");
    }
}
