<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Event;

class Information extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'can_required',
    ];

    protected function casts(): array
    {
        return [
            'can_required' => 'boolean',
        ];
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_information')->withPivot('required');
    }
}
