<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class territories extends Model
{
    use HasFactory;
    protected $fillable = [
        'Name',
    ];

    public function territory()
    {
        return $this->hasOne(official::class, 'Territory_Id');
    }
}
