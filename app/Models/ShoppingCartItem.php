<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShoppingCartItem
 * 
 * @property int $id
 * @property int|null $cart_id
 * @property int|null $product_item_id
 * @property int|null $qty
 * 
 * @property ProductItem|null $product_item
 * @property ShoppingCart|null $shopping_cart
 *
 * @package App\Models
 */
class ShoppingCartItem extends Model
{
	protected $table = 'shopping_cart_item';
	public $timestamps = false;

	protected $casts = [
		'cart_id' => 'int',
		'product_item_id' => 'int',
		'qty' => 'int'
	];

	protected $fillable = [
		'cart_id',
		'product_item_id',
		'qty'
	];

	public function product_item()
	{
		return $this->belongsTo(ProductItem::class);
	}

	public function shopping_cart()
	{
		return $this->belongsTo(ShoppingCart::class, 'cart_id');
	}
}
