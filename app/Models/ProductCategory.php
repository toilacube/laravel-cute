<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductCategory
 * 
 * @property int $id
 * @property int|null $parent_category_id
 * @property string|null $category_name
 * @property string|null $category_slug
 * 
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class ProductCategory extends Model
{
	protected $table = 'product_category';
	public $timestamps = false;

	protected $casts = [
		'parent_category_id' => 'int'
	];

	protected $fillable = [
		'parent_category_id',
		'category_name',
		'category_slug'
	];

	public function products()
	{
		return $this->hasMany(Product::class, 'category_id');
	}
	public function categories()
	{
		return $this->hasMany(ProductCategory::class, 'parent_category_id');
	}
}
