<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierDetails extends Model
{
    use HasFactory;

    protected $fillable = [
      
        'name_shop',
        'supplier_type_id',
        'account_holder',
        'account_number',
        'bank_id',
        'address',
        'user_id'
    ];

    public function Bank(){

        return $this->hasOne(Banks::class,'id','bank_id');

    }

    public function SupplieType(){

        return $this->hasOne(SupplierTypes::class,'id','supplier_type_id');

    }

}
