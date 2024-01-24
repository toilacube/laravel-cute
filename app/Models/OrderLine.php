<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderLine
 *
 * @property int $id
 * @property int|null $product_item_id
 * @property int|null $order_id
 * @property int|null $qty
 * @property int|null $price
 *
 * @property ShopOrder|null $shop_order
 * @property ProductItem|null $product_item
 * @property Collection|UserReview[] $user_reviews
 *
 * @package App\Models
 */
class OrderLine extends Model
{
	protected $table = 'order_line';
	public $timestamps = false;

	protected $casts = [
		'product_item_id' => 'int',
		'order_id' => 'int',
		'qty' => 'int',
		'price' => 'int'
    ];

	protected $fillable = [
		'product_item_id',
		'order_id',
		'qty',
		'price'
	];

	public function shop_order()
	{
		return $this->belongsTo(ShopOrder::class, 'order_id');
	}

	public function product_item()
	{
		return $this->belongsTo(ProductItem::class);
	}

	public function user_reviews()
	{
		return $this->hasMany(UserReview::class, 'ordered_product_id');
	}

}
