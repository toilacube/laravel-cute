<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPaymentMethod
 * 
 * @property int $id
 * @property int|null $user_id
 * @property int|null $payment_type_id
 * @property string|null $provider
 * @property string|null $account_number
 * @property Carbon|null $expiry_date
 * @property int|null $is_default
 * 
 * @property PaymentType|null $payment_type
 * @property User|null $user
 * @property Collection|ShopOrder[] $shop_orders
 *
 * @package App\Models
 */
class UserPaymentMethod extends Model
{
	protected $table = 'user_payment_method';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'payment_type_id' => 'int',
		'expiry_date' => 'datetime',
		'is_default' => 'int'
	];

	protected $fillable = [
		'user_id',
		'payment_type_id',
		'provider',
		'account_number',
		'expiry_date',
		'is_default'
	];

	public function payment_type()
	{
		return $this->belongsTo(PaymentType::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function shop_orders()
	{
		return $this->hasMany(ShopOrder::class, 'payment_method_id');
	}
}
