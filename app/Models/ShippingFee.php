<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShippingFee
 * 
 * @property int $id
 * @property int|null $shipping_address
 * @property int|null $value
 * 
 * @property Address|null $address
 *
 * @package App\Models
 */
class ShippingFee extends Model
{
	protected $table = 'shipping_fee';
	public $timestamps = false;

	protected $casts = [
		'shipping_address' => 'int',
		'value' => 'int'
	];

	protected $fillable = [
		'shipping_address',
		'value'
	];

	public function address()
	{
		return $this->belongsTo(Address::class, 'shipping_address');
	}
}
