<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductItem
 * 
 * @property int $id
 * @property int|null $product_id
 * @property string|null $size
 * @property string|null $color
 * @property string|null $color_image
 * @property int|null $qty_in_stock
 * 
 * @property Product|null $product
 * @property Collection|OrderLine[] $order_lines
 * @property Collection|ProductItemImage[] $product_item_images
 * @property Collection|ShoppingCartItem[] $shopping_cart_items
 *
 * @package App\Models
 */
class ProductItem extends Model
{
	protected $table = 'product_item';
	public $timestamps = false;

	protected $casts = [
		'product_id' => 'int',
		'qty_in_stock' => 'int'
	];

	protected $fillable = [
		'product_id',
		'size',
		'color',
		'color_image',
		'qty_in_stock'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function order_lines()
	{
		return $this->hasMany(OrderLine::class);
	}

	public function product_item_images()
	{
		return $this->hasMany(ProductItemImage::class);
	}

	public function shopping_cart_items()
	{
		return $this->hasMany(ShoppingCartItem::class);
	}
}
