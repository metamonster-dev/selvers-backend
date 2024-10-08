<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPayable extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'event_id',
        'type',
        'start_date',
        'end_date',
        'price',
        'price_url',
    ];
}
