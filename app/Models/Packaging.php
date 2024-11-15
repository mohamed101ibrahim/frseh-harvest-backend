<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packaging extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'kinds');
    }
}