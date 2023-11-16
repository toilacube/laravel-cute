<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserReview
 * 
 * @property int $id
 * @property int|null $user_id
 * @property int|null $ordered_product_id
 * @property int|null $rating_value
 * @property string|null $comment
 * 
 * @property OrderLine|null $order_line
 * @property User|null $user
 *
 * @package App\Models
 */
class UserReview extends Model
{
	protected $table = 'user_review';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'ordered_product_id' => 'int',
		'rating_value' => 'int'
	];

	protected $fillable = [
		'user_id',
		'ordered_product_id',
		'rating_value',
		'comment'
	];

	public function order_line()
	{
		return $this->belongsTo(OrderLine::class, 'ordered_product_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
