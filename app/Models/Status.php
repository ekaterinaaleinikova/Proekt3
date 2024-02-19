<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';

    const CREATED = 1;
    const PAID = 2;
    const SHIPPED = 3;
    const DELIVERED = 4;

    protected $fillable = [
        'name',
    ];


}
