<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FooterContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'FooterContentId';
    protected $table = 'footercontent';
    protected $guarded = [];
    
    const CREATED_AT = 'FooterContentCreatedAt';
    const UPDATED_AT = 'FooterContentUpdatedAt';
    const DELETED_AT = 'FooterContentDeletedAt';
}
