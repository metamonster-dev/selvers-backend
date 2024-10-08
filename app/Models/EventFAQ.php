<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFAQ extends Model
{
    use HasFactory;

    protected $table = 'event_faqs';
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'question',
        'answer',
    ];
}
