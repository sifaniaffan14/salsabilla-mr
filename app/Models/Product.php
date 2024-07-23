<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'ProductId';
    protected $table = 'Product';
    protected $guarded = [];
    
    const CREATED_AT = 'ProductCreatedAt';
    const UPDATED_AT = 'ProductUpdatedAt';
    const DELETED_AT = 'ProductDeletedAt';
}
