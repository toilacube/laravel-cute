<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderStatus
 * 
 * @property int $id
 * @property string|null $status
 * 
 * @property Collection|ShopOrder[] $shop_orders
 *
 * @package App\Models
 */
class OrderStatus extends Model
{
	protected $table = 'order_status';
	public $timestamps = false;

	protected $fillable = [
		'status'
	];

	public function shop_orders()
	{
		return $this->hasMany(ShopOrder::class, 'order_status');
	}
}
