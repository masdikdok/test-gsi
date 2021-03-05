<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Karyawan extends Authenticatable
{
    use HasFactory;

    protected $guarded = ['npk'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'npk',
        'nama',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function login(){
        return $this->hasOne('App\Models\Login', 'npk', 'npk')->orderBy('created_at', 'DESC');
    }

    public function getAuthPassword(){
        return $this->login->password;
    }
}
