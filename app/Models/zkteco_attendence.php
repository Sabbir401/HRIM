<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class zkteco_attendence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time',
    ];
}
