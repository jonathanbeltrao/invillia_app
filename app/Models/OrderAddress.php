<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_address';

    protected $fillable = [
        'order_id',
        'name',
        'address',
        'city',
        'country'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
