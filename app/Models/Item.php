<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['name','weight','season','size','type','age','category_id'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function packagings()
    {
        return $this->belongsToMany(Packaging::class, 'item_packaging');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'item_order');
    }
    
}