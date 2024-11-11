<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Takes extends Model
{
    use HasFactory;
    protected $fillable = ['item_id','order_id','date'];

}