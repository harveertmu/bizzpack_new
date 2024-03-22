<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productCodes extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'id',
        'vendor_id',
        'product_code',
        'product_code_description',
        'insert_by',
        'status'
    ];

    public $sortable = [
        'product_code',
        
    ];
    protected $guarded = [
        'id',
    ];

    public function vendorDetails(){

        return $this->belongsTo(User::class, 'vendor_id', 'id');

    }
    
    
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where('product_code', 'like', '%' . $search . '%');

            $query->orWhereHas('vendorDetails', function ($query) use ($search ) {
                $query->where('name', 'like', '%' . $search . '%');
            });
          
            return $query;
        });
        
    }

    public function product(){
        return $this->hasMany(Product::class,'product_code','id');
    }
}
