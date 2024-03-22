<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetails extends Model
{
    use HasFactory;
    protected $fillable = [
      
        'account_holder',
        'account_number',
        'bank_id',
        'address',
        'user_id'
    ];

    public function Bank(){

        return $this->hasOne(Banks::class,'id','bank_id');

    }
}
