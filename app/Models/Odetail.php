<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Odetail extends Model
{
    const INDISPONIBLE = 'indisponible';
    const DISPONIBLE = 'disponible';

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function order()
    {

        return $this->belongsTo(Order::class);
    }

    public function product()
    {

        return $this->hasOne(Product::class);
    }
}
