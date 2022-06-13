<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $guarded = ['id'];

    public function service()
    {

        return $this->belongsTo(Service::class);
    }

    public function odetails()
    {

        return $this->hasMany(Odetail::class); //belongsToMany ???
    }

    public static function getPrice($id)
    {
        return Product::where('id', $id)->value('price');
    }
}
