<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReg extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', 'password',
    ];

    protected $table = 'usersreg';
}


