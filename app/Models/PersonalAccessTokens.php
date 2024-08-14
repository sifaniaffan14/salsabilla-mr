<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessTokens extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'personal_access_tokens';
    protected $guarded = [];
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const LAST_EXPIRED_AT = 'expires_at';
}
