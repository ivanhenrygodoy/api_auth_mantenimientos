<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MntProduct extends Model
{
    use HasFactory;

    protected $table = 'mnt_product';

    protected $guarded = [
        'id'
    ];

}
