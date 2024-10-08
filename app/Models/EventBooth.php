<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBooth extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'event_id',
        'number',
        'name',
        'position',
        'url',
    ];
}
