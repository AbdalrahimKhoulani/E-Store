<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int $quantity
 * @property string $image
 * @property string $category_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderDetails[] $orderDetails
 * @property-read int|null $order_details_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereQuantity($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'price',
        'quantity',
        'image',
        'category_name'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'product_id', 'id');
    }
}
