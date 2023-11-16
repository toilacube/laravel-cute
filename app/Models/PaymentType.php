<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentType
 * 
 * @property int $id
 * @property string|null $value
 * 
 * @property Collection|UserPaymentMethod[] $user_payment_methods
 *
 * @package App\Models
 */
class PaymentType extends Model
{
	protected $table = 'payment_type';
	public $timestamps = false;

	protected $fillable = [
		'value'
	];

	public function user_payment_methods()
	{
		return $this->hasMany(UserPaymentMethod::class);
	}
}
