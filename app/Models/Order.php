<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Order extends Model
{
    const EN_ATTENTE = 'En Attente';
    const EN_COURS = 'En Cours';
    const TERMINEE = 'Terminée';
    const ANNULEE = "Annulee";

    use HasApiTokens;
    use HasFactory;

    protected $guarded = [
        'id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function odetails()
    {
        return $this->hasMany(Odetail::class);
    }
}
