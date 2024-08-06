<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'SocialMediaId';
    protected $table = 'socialmedia';
    protected $guarded = [];
    
    const CREATED_AT = 'SocialMediaCreatedAt';
    const UPDATED_AT = 'SocialMediaUpdatedAt';
    const DELETED_AT = 'SocialMediaDeletedAt';
}
