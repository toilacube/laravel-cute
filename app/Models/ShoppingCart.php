<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ShoppingCart
 * 
 * @property int $id
 * @property int|null $user_id
 * 
 * @property User|null $user
 * @property Collection|ShoppingCartItem[] $shopping_cart_items
 *
 * @package App\Models
 */
class ShoppingCart extends Model
{
	protected $table = 'shopping_cart';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function shopping_cart_items()
	{
		return $this->hasMany(ShoppingCartItem::class, 'cart_id');
	}
}
