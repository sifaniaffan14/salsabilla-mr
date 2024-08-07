<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'ProductDetailId';
    protected $table = 'productdetail';
    protected $guarded = [];
    
    const CREATED_AT = 'ProductDetailCreatedAt';
    const UPDATED_AT = 'ProductDetailUpdatedAt';
    const DELETED_AT = 'ProductDetailDeletedAt';

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductDetailProductId', 'ProductId');
    }
}
