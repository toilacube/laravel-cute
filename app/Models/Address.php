<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * 
 * @property int $id
 * @property string|null $address_line
 * @property string|null $province
 * @property string|null $district
 * @property string|null $commune
 * 
 * @property Collection|ShippingFee[] $shipping_fees
 * @property Collection|ShopOrder[] $shop_orders
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Address extends Model
{
	protected $table = 'address';
	public $timestamps = false;

	protected $fillable = [
		'address_line',
		'province',
		'district',
		'commune'
	];

	public function shipping_fees()
	{
		return $this->hasMany(ShippingFee::class, 'shipping_address');
	}

	public function shop_orders()
	{
		return $this->hasMany(ShopOrder::class, 'shipping_address');
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'user_address')
					->withPivot('is_default');
	}
	
}
