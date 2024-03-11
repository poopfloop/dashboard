<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'firstname',
        'lastname',
        'phone',
        'wilaya',
        'city',
        'address',
        'status',
        'notes',
        'total',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'price']);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected $casts = [
        'status' => OrderStatusEnum::class
    ];
}
