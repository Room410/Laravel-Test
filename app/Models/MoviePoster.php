<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoviePoster extends Model
{
    use HasFactory;
    protected $fillable = ['movie_id', 'poster'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
