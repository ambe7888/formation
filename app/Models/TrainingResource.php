<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'title',
        'type',
        'url',
        'description',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
