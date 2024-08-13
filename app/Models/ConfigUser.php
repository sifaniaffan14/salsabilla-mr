<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'UserId';
    protected $table = 'configusers';
    protected $guarded = [];
    
    const CREATED_AT = 'UserCreatedAt';
    const UPDATED_AT = 'UserUpdatedAt';
    const DELETED_AT = 'UserDeletedAt';

}
