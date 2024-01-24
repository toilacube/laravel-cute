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

class ReviewService
{
    public function getOne($reviewId)
    {

        $review = UserReview::find($reviewId);

        if (!$review) {
            return "Review not found";
        }

        $item = ProductItem::where('id', $review->ordered_product_id)->first();

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
        return $userReviewDTO->toArray();
    }

    public function getAll()
    {
        $reviews = UserReview::get();
        $userReviewDTOs = [];
        foreach ($reviews as $review) {

            $item = ProductItem::where('id', $review->ordered_product_id)->first();
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

    public function getOfProduct($productId)
    {

        // get all the product_item_id of this product
        $itemIds = ProductItem::where('product_id', $productId)->pluck('id');
        $userReviewDTOs = [];

        $total_cnt = 0;
        $total_rating = 0;

        foreach ($itemIds as $id) {
            $reviews = UserReview::where('ordered_product_id', $id)->get(['id', 'rating_value']);
            if ($reviews->count() == 0) {
                continue;
            }
            foreach ($reviews as $review) {
                $total_cnt++;
                $total_rating += $review['rating_value'];
                $userReviewDTOs[] = $this->getOne($review['id']);
            }
        }
        if ($total_cnt == 0) {
            return [];
        }
        $avg_rating = round($total_rating / $total_cnt, 1);
        $productReviewDTO = new ProductReviewDTO(
            $total_cnt,
            $avg_rating,
            $userReviewDTOs
        );

        return $productReviewDTO->toArray();
    }

    public function add($addReviewDTO)
    {
        $review = new UserReview();
        $review->user_id = Auth::user()->id;
        $review->ordered_product_id = $addReviewDTO->productItemId;

        $productId = $addReviewDTO->productItemId;

        // check if this user has ordered this item or not
        $orderId = ShopOrder::where('user_id', Auth::user()->id)->where('order_status', 1)->pluck('id');

        // get the all the product_item that has product_id is $productId
        $itemsId = ProductItem::where('product_id', $productId)->pluck('id');

        $item = OrderLine::whereIn('order_id', $orderId)->whereIn('product_item_id', $itemsId)->first();

        if (!$item) {
            return response("You haven't ordered this product yet", 400);
        }

        // check if this user has reviewed this item or not
        $reviewed = UserReview::where('user_id', Auth::user()->id)->where('ordered_product_id', $addReviewDTO->productItemId)->first();
        if ($reviewed) {
            return response("You have reviewed this product", 400);
        }
        $review->rating_value = $addReviewDTO->ratingValue;
        $review->comment = $addReviewDTO->comment;
        $review->created_at = now();
        $review->save();
        return $this->getOne($review->id);
    }

    public function addItem($addReviewDTO)
    {
        $review = new UserReview();
        $review->user_id = Auth::user()->id;


        $productItemId = $addReviewDTO->productItemId;

        // get the product_id of the item
        $productId = ProductItem::where('id', $productItemId)->pluck('product_id')->first();

        // check if this user has ordered this item or not
        $orderId = ShopOrder::where('user_id', Auth::user()->id)->where('order_status', 1)->pluck('id');

        $item = OrderLine::whereIn('order_id', $orderId)->where('product_item_id', $productItemId)->first();

        if (!$item) {
            return response("You haven't ordered this product yet", 400);
        }

        // check if this user has reviewed this item or not
        $reviewed = UserReview::where('user_id', Auth::user()->id)->where('ordered_product_id', $productId)->first();
        if ($reviewed) {
            return response("You have reviewed this product", 400);
        }

        $review->ordered_product_id = $productId;
        $review->rating_value = $addReviewDTO->ratingValue;
        $review->comment = $addReviewDTO->comment;
        $review->created_at = now();
        $review->save();
        return $this->getOne($review->id);
    }

    public function update($updateReviewDTO)
    {
        $review = UserReview::find($updateReviewDTO->id);

        if (!$review) {
            return "Review not found";
        }

        $review->rating_value = $updateReviewDTO->ratingValue;
        $review->comment = $updateReviewDTO->comment;
        $review->save();
        return $this->getOne($review->id);
    }

    public function delete($reviewId)
    {
        $review = UserReview::find($reviewId);

        if (!$review) {
            return "Review not found";
        }

        $review->delete();
        return "Review deleted";
    }
}
