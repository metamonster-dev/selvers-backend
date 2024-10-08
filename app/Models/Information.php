<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\EventRecurit;

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

    public function recurits(): BelongsToMany
    {
        return $this->belongsToMany(EventRecurit::class, 'event_recruit_information')->withPivot('required');
    }
}
