<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zkteco_Users extends Model
{
    use HasFactory;

    protected $fillable = [
        'User_Id',
        'Name',
        'Card_No',
    ];
}
