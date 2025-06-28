<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi mass-assignment.
     */
    protected $fillable = [
        'title',
        'description',
        'photo',
        'ingredients',
        'steps',
        'duration',
        'servings',
    ];
}
