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
 * @property Carbon|null $order_date
 * @property int|null $payment_method
 * @property string|null $shipping_address
 * @property int|null $shipping_method
 * @property int|null $order_total
 * @property int|null $order_status
 * 
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
		'payment_method' => 'int',
		'shipping_method' => 'int',
		'order_total' => 'int',
		'order_status' => 'int'
	];

	protected $fillable = [
		'user_id',
		'order_date',
		'payment_method',
		'shipping_address',
		'shipping_method',
		'order_total',
		'order_status'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order_lines()
	{
		return $this->hasMany(OrderLine::class, 'order_id');
	}
}
