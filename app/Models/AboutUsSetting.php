<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutUsSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'AboutUsId';
    protected $table = 'aboutus';
    protected $guarded = [];
    
    const CREATED_AT = 'AboutUsCreatedAt';
    const UPDATED_AT = 'AboutUsUpdatedAt';
    const DELETED_AT = 'AboutUsDeletedAt';
}
