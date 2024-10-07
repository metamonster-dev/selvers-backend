<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\User;

class UserCompany extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'company_name',
        'company_id',
        'company_id_file',
        'company_id_file_name',
        'name',
        'department',
        'position',
        'contact',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
