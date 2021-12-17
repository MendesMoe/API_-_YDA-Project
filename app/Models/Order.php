<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Order extends Model
{
    use HasApiTokens;

    protected $guarded = [
        'id',
        'user_id'
    ];

    public function user()
    {

        return $this->belongsTo(User::class, 'user_id');
    }

    public function odetails()
    {
        return $this->hasMany(Odetail::class, 'order_id')
            ->selectRaw('SUM(odetails.price_product) as total')
            ->groupBy('order_id');
    }
}
