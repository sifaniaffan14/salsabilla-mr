<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JumbotronSetting extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'JumbotronId';
    protected $table = 'jumbotron';
    protected $guarded = [];
    
    const CREATED_AT = 'JumbotronCreatedAt';
    const UPDATED_AT = 'JumbotronUpdatedAt';
    const DELETED_AT = 'JumbotronDeletedAt';
}
