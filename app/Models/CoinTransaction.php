<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'name',
        'email',
        'phone',
        'proof',
    ];
}
