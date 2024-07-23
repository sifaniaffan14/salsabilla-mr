<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasFactory;

    public function toArray()
    {
        $array = parent::toArray();
    
        // Convert the keys of attributes to PascalCase
        $pascalCaseArray = [];
        foreach ($array as $key => $value) {
            $pascalCaseKey = Str::studly($key);
            $pascalCaseArray[$pascalCaseKey] = $value;
        }
    
        // Loop through the model's relations
        foreach ($this->getRelations() as $relationName => $relationValue) {
            // Change the key from the relation name to PascalCase
            $pascalCaseKey = Str::studly($relationName);
            
            // Check if the relation is a method (callable) before trying to access it
            if (method_exists($this, $relationName)) {
                $pascalCaseArray[$pascalCaseKey] = $this->$relationName;
            } else {
                $pascalCaseArray[$pascalCaseKey] = $array[$relationName];
            }
            unset($pascalCaseArray[$relationName]);
        }
    
        return $pascalCaseArray;
    }

    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);
    
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }
    
        return $value;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
    
}
