<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Information;

class EventRecurit extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'event_id',
        'type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
    ];


    public function information(): BelongsToMany
    {
        return $this->belongsToMany(Information::class, 'event_recruit_information', 'recurit_id')->withPivot('required');
    }

}
