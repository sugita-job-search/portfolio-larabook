<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WantToReadBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
    ];
    
    public static $rules = [
        'book_id' => ['required', 'integer', 'exists:books,id'],
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
