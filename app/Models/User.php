<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

/**
 * Class User
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $gender
 * @property int|null $weight
 * @property int|null $height
 * @property string|null $birth
 * @property string|null $password
 * 
 * @property Collection|ShopOrder[] $shop_orders
 * @property Collection|ShoppingCart[] $shopping_carts
 * @property Collection|Address[] $addresses
 * @property Collection|UserPaymentMethod[] $user_payment_methods
 * @property Collection|UserReview[] $user_reviews
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject, CanResetPassword
{
	use Notifiable, CanResetPasswordTrait;



	// Rest omitted for brevity

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}
	protected $table = 'user';
	public $timestamps = false;

	protected $casts = [
		'weight' => 'int',
		'height' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'email',
		'phone_number',
		'gender',
		'weight',
		'height',
		'birth',
		'password'
	];

	public function shop_orders()
	{
		return $this->hasMany(ShopOrder::class);
	}

	public function shopping_carts()
	{
		return $this->hasOne(ShoppingCart::class);
	}

	public function addresses()
	{
		return $this->belongsToMany(Address::class, 'user_address')
			->withPivot('is_default', 'name', 'phone_number');
	}

	public function user_payment_methods()
	{
		return $this->hasMany(UserPaymentMethod::class);
	}

	public function user_reviews()
	{
		return $this->hasMany(UserReview::class);
	}
}
