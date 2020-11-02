<?php

namespace App\Models;

use App\Traits\Models\FillableFields;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, FillableFields;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getLogoPath()
    {
        return "/adminlte/img/avatar_1.png";
    }

    public function scopeSearch($query, $searchTerm = '')
    {
        return $query->where(function ($query) use ($searchTerm) {
            $query->orwhere('name', 'like', "%" . $searchTerm . "%")
                ->orWhere('email', 'like', "%" . $searchTerm . "%");
        });
    }
}
