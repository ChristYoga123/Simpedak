<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalOwner extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function Animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function AnimalSchedules()
    {
        return $this->hasMany(AnimalSchedule::class);
    }
}
