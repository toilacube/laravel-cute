<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ShopOrder
 * 
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property Carbon|null $order_date
 * @property int|null $payment_method_id
 * @property int|null $shipping_address
 * @property int|null $shipping_method
 * @property int|null $order_total
 * @property int|null $order_status
 * 
 * @property UserPaymentMethod|null $user_payment_method
 * @property Address|null $address
 * @property User|null $user
 * @property Collection|OrderLine[] $order_lines
 *
 * @package App\Models
 */
class ShopOrder extends Model
{
	protected $table = 'shop_order';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'order_date' => 'datetime',
		'payment_method_id' => 'int',
		'shipping_address' => 'int',
		'shipping_method' => 'int',
		'order_total' => 'int',
		'order_status' => 'int'
	];

	protected $fillable = [
		'user_id',
		'name',
		'order_date',
		'payment_method_id',
		'shipping_address',
		'shipping_method',
		'order_total',
		'order_status'
	];

	public function user_payment_method()
	{
		return $this->belongsTo(UserPaymentMethod::class, 'payment_method_id');
	}

	public function address()
	{
		return $this->belongsTo(Address::class, 'shipping_address');
	}

	public function shipping_method()
	{
		return $this->belongsTo(ShippingMethod::class, 'shipping_method');
	}

	public function order_status()
	{
		return $this->belongsTo(OrderStatus::class, 'order_status');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order_lines()
	{
		return $this->hasMany(OrderLine::class, 'order_id');
	}
}
