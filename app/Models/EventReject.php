<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventReject extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'category',
        'img',
        'date',
        'time',
        'payable',
        'progress',
        'position',
        'content',
        'survey',
        'faq',
        'contact',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'boolean',
            'category' => 'boolean',
            'img' => 'boolean',
            'date' => 'boolean',
            'time' => 'boolean',
            'category' => 'boolean',
            'payable' => 'boolean',
            'progress' => 'boolean',
            'position' => 'boolean',
            'content' => 'boolean',
            'recurit_date' => 'boolean',
            'recurit_type' => 'boolean',
            'recurit_information' => 'boolean',
            'tag' => 'boolean',
            'survey' => 'boolean',
            'faq' => 'boolean',
            'contact' => 'boolean',
        ];
    }
}
