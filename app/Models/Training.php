<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'category_id',
        'description',
        'start_date',
        'planned_month',
        'location',
        'price',
        'promo_price',
        'seats',
        'image_url',
        'is_active',
        'is_featured',
        'hero_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'price' => 'integer',
        'promo_price' => 'integer',
        'seats' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'hero_order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    public function resources()
    {
        return $this->hasMany(TrainingResource::class);
    }

    public function bundles()
    {
        return $this->belongsToMany(Bundle::class)->withTimestamps();
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->category?->name ?? $this->category;
    }
}