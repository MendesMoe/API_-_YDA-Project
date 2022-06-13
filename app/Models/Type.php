<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Type extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $guarded = ['id'];

    public function services()
    {

        return $this->hasMany(Service::class);
    }
}
