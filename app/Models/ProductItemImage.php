<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductItemImage
 * 
 * @property int $id
 * @property int|null $product_item_id
 * @property string|null $url
 * 
 * @property ProductItem|null $product_item
 *
 * @package App\Models
 */
class ProductItemImage extends Model
{
	protected $table = 'product_item_image';
	public $timestamps = false;

	protected $casts = [
		'product_item_id' => 'int'
	];

	protected $fillable = [
		'product_item_id',
		'url'
	];

	public function product_item()
	{
		return $this->belongsTo(ProductItem::class);
	}
}
