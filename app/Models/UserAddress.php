<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAddress
 * 
 * @property int|null $user_id
 * @property int|null $address_id
 * @property int|null $is_default
 * 
 * @property Address|null $address
 * @property User|null $user
 *
 * @package App\Models
 */
class UserAddress extends Model
{
	protected $table = 'user_address';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'address_id' => 'int',
		'is_default' => 'int'
	];

	protected $fillable = [
		'user_id',
		'address_id',
		'is_default'
	];

	public function address()
	{
		return $this->belongsTo(Address::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
