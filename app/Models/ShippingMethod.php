<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ShippingMethod
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|ShopOrder[] $shop_orders
 *
 * @package App\Models
 */
class ShippingMethod extends Model
{
	protected $table = 'shipping_method';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function shop_orders()
	{
		return $this->hasMany(ShopOrder::class, 'shipping_method');
	}
}
