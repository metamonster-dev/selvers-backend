<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;
use App\Models\Event;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_interests');
    }

    public function event(): BelongsToMany
    {
        return $this->hasMany(Event::class);
    }
}
