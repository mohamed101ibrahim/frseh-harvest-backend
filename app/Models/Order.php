<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['total','status','user_id','admin_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function takes()
    {
        return $this->hasMany(Takes::class);
    }


    public function items()
    {
        return $this->belongsToMany(Item::class, 'takes')
                    ->withPivot('quantity')
                    ->withoutTimestamps();
    }
}