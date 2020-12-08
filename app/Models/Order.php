<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'customer_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function customer()
    {
        $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function address()
    {
        return $this->hasOne(OrderAddress::class);
    }
}
