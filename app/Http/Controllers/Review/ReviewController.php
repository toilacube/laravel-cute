<?php

namespace App\Http\Controllers\Review;

use Illuminate\Http\Request;
use App\DTOs\Requests\AddReviewDTO;
use App\Http\Controllers\Controller;
use App\DTOs\Requests\UpdateReviewDTO;
use App\Services\Review\ReviewService;

class ReviewController extends Controller
{
    //
    public function __construct(private ReviewService $reviewService)
    {
    }

    public function getAll()
    {
        return $this->reviewService->getAll();
    }

    public function getOne(Request $request)
    {
        // get reviewId from request
        $reviewId = $request->reviewId;

        return $this->reviewService->getOne($reviewId);
    }

    public function getOfProduct(Request $request)
    {
        $productId = $request->productId;
        return $this->reviewService->getOfProduct($productId);
    }

    public function add(Request $request)
    {
        
        $addReviewDTO = new AddReviewDTO(
            $request->productItemId,
            $request->ratingValue,
            $request->comment
        );

        return $this->reviewService->add($addReviewDTO);
    }

    public function update(Request $request)
    {
        $updateReviewDTO = new UpdateReviewDTO(
            $request->id,
            $request->ratingValue,
            $request->comment
        );

        return $this->reviewService->update($updateReviewDTO);
    }

    public function delete(Request $request)
    {
        $reviewId = $request->reviewId;
        return $this->reviewService->delete($reviewId);
    }
}
