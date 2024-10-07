<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\UserCompany;
use App\Models\UserSocial;

use App\Models\Category;
use App\Models\TermsOfUse;

use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verity_token',
        'password',
        'birth',
        'sex',
        'contact',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'password_updated_at',
        'remember_token',
        'email_verity_token',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_updated_at' => 'datetime',
            'sex' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    public function company(): HasOne
    {
        return $this->hasOne(UserCompany::class);
    }

    public function social(): HasOne
    {
        return $this->hasOne(UserSocial::class);
    }

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'user_interests');
    }

    public function termsOfUses(): BelongsToMany
    {
        return $this->belongsToMany(TermsOfUse::class, 'user_terms_of_uses')->withPivot('agree')->withTimestamps();
    }

    public function resetPassword() : string
    {
        $password = Str::random(8);
        $this->password = bcrypt($password);
        $this->password_updated_at = now();
        $this->save();
        return $password;
    }
}
