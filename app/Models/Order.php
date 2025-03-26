<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_name', 'quantity', 'total_price', 'shipping_address', 'status', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
