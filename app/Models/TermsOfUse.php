<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User;

class TermsOfUse extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'context',
        'require',
    ];

    public function termsOfUses(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_terms_of_uses')->withPivot('agree')->withTimestamps();
    }
}
