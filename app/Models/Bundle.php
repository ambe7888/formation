<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image_url',
        'is_featured',
        'hero_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'hero_order'  => 'integer',
    ];

    public function trainings()
    {
        return $this->belongsToMany(Training::class)->withTimestamps();
    }
}
