<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heart extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommendation_id',
    ];
    
    public static $rules = [
        'recommendation_id' => ['required', 'integer', 'exists:recommendations,id'],
    ];
}
