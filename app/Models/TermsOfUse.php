<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User;

class TermsOfUse extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'title',
        'context',
        'required',
    ];

    public function termsOfUses(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_terms_of_uses')->withPivot('agree')->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
        ];
    }
}
