<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Sortable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'photo',
        'name',
        'username',
        'email',
        'password',
        'phone_number',
        'photo',
        'zip',
        'city',
        'state',
        'street_address',
        'address_apt',
        'company_name',
        'gst_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $sortable = [
        'name',
        'email',
        'username',
        'phone_number'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')->orWhere('username', 'like', '%' . $search . '%');
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
    */
    public function getRouteKeyName()
    {
        return 'username';
    }

    public function SupplierDetails(){

        return $this->hasOne(SupplierDetails::class)->with(['Bank','SupplieType']);

    }
    public function CustomerDetails(){

        return $this->hasOne(CustomerDetails::class)->with(['Bank']);

    }
    
}
