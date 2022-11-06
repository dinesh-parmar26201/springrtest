<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'date_of_joining',
        'date_of_leaving',
        'still_working',
        'image',
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

    public function getExperienceAttribute()
    {
        $diff = Carbon::createFromFormat('m/d/Y', $this->date_of_joining)->diff(($this->date_of_leaving ? Carbon::createFromFormat('m/d/Y', $this->date_of_leaving) : now()));
        return (($diff->y > 0) ? "$diff->y years" : "") . " " . (($diff->m > 0) ? "$diff->m months" : "");
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     self::deleting(function (User $user) {
    //         if (file_exists(public_path("uploads" . "\/" . $user->image))) {
    //             @unlink(public_path("uploads" . "\/" . $user->image));
    //         }
    //         $user->delete();
    //     });
    // }

    public function delete()
    {
        if (file_exists(public_path("uploads" . "\/" . $this->image))) {
            @unlink(public_path("uploads" . "\/" . $this->image));
        }
        parent::delete();
    }
}
