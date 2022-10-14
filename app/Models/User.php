<?php

namespace App\Models;

use Illuminate\Support\Facades\Mail;
use App\Mail\MagicLoginLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function firm()
    {

        return $this->belongsTo(Firm::class);
    }

    public function orders()
    {

        return $this->hasMany(Order::class);
    }

    public function loginTokens()
    {
        return $this->hasMany(LoginToken::class);
    }

    public function sendLoginLink()
    {
        $plaintext = Str::random(32);
        $token = $this->loginTokens()->create([
            'token' => hash('sha256', $plaintext),
            'expires_at' => now()->addDays(3) // demander aux filles
        ]);
        // todo send email
        Mail::to($this->email)->queue(new MagicLoginLink($plaintext, $token->expires_at));
    }

    public function getFirmId()
    {
        return $this->firm_id;
    }

    public static function getUsersByFirmsByStatus($firmId)
    {
        $users = User::where('firm_id', $firmId)->has('orders')->with('orders.odetails')->get();

        foreach ($users as $user) {

            $ordersOnHold[] = $user->orders->filter(function ($order) {
                return ($order->status != "terminee" && $order->status != "annule");
            });

            if (count($ordersOnHold) > 1) {
                $user->orders = $ordersOnHold;
            }
        }
        return $users;
    }
}
