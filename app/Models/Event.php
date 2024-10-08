<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\EventPayable;
use App\Models\EventRecurit;
use App\Models\EventSurvey;
use App\Models\EventFAQ;
use App\Models\EventBooth;
use App\Models\EventReject;

use App\Models\Category;
use App\Models\Tag;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'img1',
        'img2',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'progress_type',
        'progress_url',
        'position1',
        'position2',
        'content',
        'contact_name',
        'contact_email',
        'contact_number',
        'xlsx',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function payable(): HasOne
    {
        return $this->hasOne(EventPayable::class);
    }

    public function recurit(): HasOne
    {
        return $this->hasOne(EventRecurit::class);
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(EventSurvey::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(EventFAQ::class);
    }

    public function booths(): HasMany
    {
        return $this->hasMany(EventBooth::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'event_tags');
    }


    public function reject(): HasOne
    {
        return $this->hasOne(EventReject::class);
    }

}
