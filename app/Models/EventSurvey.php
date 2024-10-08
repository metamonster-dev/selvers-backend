<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSurvey extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'event_id',
        'type',
        'options',
        'require',
    ];

    protected function casts(): array
    {
        return [
            'require' => 'boolean',
        ];
    }
}
