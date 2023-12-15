<?php
namespace App\Services\Review;

use App\Models\User;
use App\Models\UserReview;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Auth;
use App\DTOs\Responses\UserReviewDTO;
use App\DTOs\Responses\ProductReviewDTO;
use App\Models\OrderLine;
use App\Models\ShopOrder;

class ReviewService{
    public function getOne($reviewId){

        $review = UserReview::find($reviewId);

        if(!$review){
            return "Review not found";
        }

        $item = ProductItem::where('id',$review->ordered_product_id)->first();

        $user = User::where('id',$review->user_id)->first();

        $userReviewDTO = new UserReviewDTO(
            $review->id,
            $review->user_id,
            $user->name,
            $review->ordered_product_id,
            $item->color,
            $item->size,
            $review->rating_value,
            $review->comment,
            $review->created_at
        );
        return $userReviewDTO->toArray();
    }

    public function getAll(){
        $reviews = UserReview::get();
        $userReviewDTOs = [];
        foreach ($reviews as $review) {

            $item = ProductItem::where('id',$review->ordered_product_id)->first();   
            $user = User::where('id', $review->user_id)->first();

            $userReviewDTO = new UserReviewDTO(
                $review->id,
                $review->user_id,
                $user->name,
                $review->ordered_product_id,
                $item->color,
                $item->size,
                $review->rating_value,
                $review->comment,
                $review->created_at
            );
            $userReviewDTOs[] = $userReviewDTO->toArray();
        }
        return $userReviewDTOs;
    }

    public function getOfProduct($productId){
        
        // get all the product_item_id of this product
        $itemIds = ProductItem::where('product_id',$productId)->pluck('id');
        $userReviewDTOs = [];

        $total_cnt = 0;
        $total_rating = 0;

        foreach($itemIds as $id){
            $reviews = UserReview::where('ordered_product_id',$id)->get(['id', 'rating_value']);
            if($reviews->count() == 0){
                continue;
            }
            foreach ($reviews as $review) {
                $total_cnt ++;
                $total_rating += $review['rating_value'];
                $userReviewDTOs[] = $this->getOne($review['id']);
            }
            
        }

        $avg_rating = round($total_rating/$total_cnt, 1);
        $productReviewDTO = new ProductReviewDTO(
            $total_cnt,
            $avg_rating,
            $userReviewDTOs
        );

        return $productReviewDTO->toArray();
    }
        
    public function add($addReviewDTO){
        $review = new UserReview();
        $review->user_id = Auth::user()->id;
        $review->ordered_product_id = $addReviewDTO->productItemId;

        // check if this user has ordered this item or not
        $orderId = ShopOrder::where('user_id', Auth::user()->id)->where('order_status', 1)->pluck('id');
        $item = OrderLine::whereIn('order_id', $orderId)->where('product_item_id', $addReviewDTO->productItemId)->first();

        if(!$item){
            return "You haven't ordered this product yet";
        }

        // check if this user has reviewed this item or not
        $reviewed = UserReview::where('user_id', Auth::user()->id)->where('ordered_product_id', $addReviewDTO->productItemId)->first();
        if($reviewed){
            return "You have reviewed this product";
        }
        $review->rating_value = $addReviewDTO->ratingValue;
        $review->comment = $addReviewDTO->comment;
        $review->save();
        return $this->getOne($review->id);
    }

    public function update($updateReviewDTO){
        $review = UserReview::find($updateReviewDTO->id);

        if(!$review){
            return "Review not found";
        }

        $review->rating_value = $updateReviewDTO->ratingValue;
        $review->comment = $updateReviewDTO->comment;
        $review->save();
        return $this->getOne($review->id);
    }

    public function delete($reviewId){
        $review = UserReview::find($reviewId);

        if(!$review){
            return "Review not found";
        }

        $review->delete();
        return "Review deleted";
    }
}