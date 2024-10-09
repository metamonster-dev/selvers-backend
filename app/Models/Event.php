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
        'category_id',
        'title',
        'img1',
        'img2',
        'event_start_date',
        'event_start_time',
        'event_end_date',
        'event_end_time',

        'payable_type',
        'payable_start_date',
        'payable_end_date',
        'payable_price',
        'payable_price_url',

        'progress_type',
        'progress_url',
        'position1',
        'position2',
        'content',

        'recurit_type',
        'recurit_start_date',
        'recurit_end_date',
        'recurit_start_time',
        'recurit_end_time',

        'is_survey',
        'is_FAQ',

        'contact_name',
        'contact_email',
        'contact_number',
        'xlsx',
    ];

    protected function casts(): array
    {
        return [
            'is_survey' => 'boolean',
            'is_FAQ' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function information(): BelongsToMany
    {
        return $this->belongsToMany(Information::class, 'event_information')->withPivot('required');
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

    public function checkBasic(): boolean
    {
        $check = true;
        $check = _isValid($check, $this->title);
        $check = _isValid($check, $this->category_id);
        $check = _isValid($check, $this->category_id);
        $check = _isValid($check, $this->img1);
        $check = _isValid($check, $this->img2);
        $check = _isValid($check, $this->event_start_date);
        $check = _isValid($check, $this->event_end_date);
        $check = _isValid($check, $this->event_start_time);
        $check = _isValid($check, $this->event_end_time);

        $check = _isValid($check, $this->payable_type);
        if ($this->payable_type == 2) {
            $check = _isValid($check, $this->payable_price2);
        } else if ($this->payable_type == 3 || $this->payable_type == 4) {
            $check = _isValid($check, $this->payable_start_date);
            $check = _isValid($check, $this->payable_end_date);
            $check = _isValid($check, $this->payable_price_url);
            $check = _isValid($check, $this->payable_price1);
            $check = _isValid($check, $this->payable_price2);
        } else if ($this->payable_type == 5) {
            $check = _isValid($check, $this->payable_price_url);
        }

        $check = _isValid($check, $this->progress_type);
        if ($this->progress_type != 0)
            $check = _isValid($check, $this->progress_url);

        if ($this->progress_type != 1) {
            $check = _isValid($check, $this->position1);
            $check = _isValid($check, $this->position2);
        }
        return $check;
    }

    public function checkDetail(): boolean
    {
        $check = true;

        $check = _isValid($check, $this->content);
        if ($this->tags()->count() > 5)
            $check = false;

        return $check;
    }

    public function checkRecurit(): boolean
    {
        $check = true;
        
        $check = _isValid($check, $this->recurit_type);
        $check = _isValid($check, $this->recurit_start_date);
        $check = _isValid($check, $this->recurit_end_date);
        $check = _isValid($check, $this->recurit_start_time);
        $check = _isValid($check, $this->recurit_end_time);

        if ($this->informations()->count() == 0)
            $check = false;

        return $check;
    }

    public function checkSurvey(): boolean
    {
        $check = true;
        
        $check = _isValid($check, $this->is_survey);
        if ($this->is_survey && $this->survey()->count() == 0)
            $check = false;

        return $check;
    }

    public function checkFAQ(): boolean
    {
        $check = true;
        
        $check = _isValid($check, $this->is_FAQ);
        $check = _isValid($check, $this->contact_name);
        $check = _isValid($check, $this->contact_email);
        $check = _isValid($check, $this->contact_number);
        if ($this->is_FAQ && $this->faqs()->count() == 0)
            $check = false;

        return $check;
    }

    public function _isValid(boolean $check, string $str) {
        return $check ? ($str != null && $str != '') : false;
    }

}
