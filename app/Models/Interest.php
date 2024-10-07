<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_interests');
    }
}
