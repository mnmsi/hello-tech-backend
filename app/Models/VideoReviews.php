<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoReviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'thumbnail',
    ];
}
