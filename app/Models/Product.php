<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property int|null $category_id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $price_int
 * @property string|null $price_str
 * 
 * @property ProductCategory|null $product_category
 * @property Collection|ProductItem[] $product_items
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'product';
	public $timestamps = false;

	protected $casts = [
		'category_id' => 'int',
		'price_int' => 'int'
	];

	protected $fillable = [
		'category_id',
		'name',
		'description',
		'price_int',
		'price_str'
	];

	public function product_category()
	{
		return $this->belongsTo(ProductCategory::class, 'category_id');
	}

	public function product_items()
	{
		return $this->hasMany(ProductItem::class);
	}
}
