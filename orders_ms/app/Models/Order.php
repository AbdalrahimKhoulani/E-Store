<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id
 * @property string $date
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderDetails[] $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['user_id','date','status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }
}
