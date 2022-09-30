<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderDetails
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $amount
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereProductId($value)
 * @mixin \Eloquent
 */
class OrderDetails extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = ['order_id','product_id','amount'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
