<?php

namespace App\Http\Controllers\Product;

use App\DTOs\Requests\AddProduct\AddProductDTO;
use App\DTOs\Requests\AddProduct\AddProductItemDTO;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\DTOs\Requests\UpdateProductDTO;
use App\Services\Products\ProductsService;
use Illuminate\Database\Eloquent\Casts\Json;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductsController extends Controller
{
    public function __construct(private ProductsService $productsService)
    {
    }
    /**
     * Display MAC HANG NGAY(casual) products.
     */
    public function products(Request $request)
    {
        if ($request->has('filter')) {
            return $this->productsService->products($request->category_slug, $request->filter);
        } else
            return $this->productsService->products($request->category_slug, "false");
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->productsService->show($id);
    }

    /**
     * Search products.
     */
    public function search(Request $request)
    {
        return $this->productsService->search($request->searchTerm);
    }

    public function update(Request $request)
    {
        $updateProductDTO = new UpdateProductDTO(
            $request->id,
            $request->categoryId,
            $request->name,
            $request->description,
            $request->priceInt,
            $request->priceStr,
        );
        return $this->productsService->update($updateProductDTO);
    }

    public function add(Request $request)
    {
        return $request;
        $productDTO = new AddProductDTO(
            $request->CategoryId,
            $request->Name,
            $request->Description,
            $request->PriceInt,
            $request->PriceStr,
            $request->ProductItems
        );

        $url = '';
        $color = "";
        $qty = 0;
        $images = [];
        $sizes = [];

        $productItemDTO = new AddProductItemDTO();

        foreach ($productDTO->productItems as $item) {


            if ($item['Size'] != null) {
                foreach ($item['Size'] as $size) {
                    $sizes[] = $size;
                }
                $productItemDTO->setSize($sizes);
            }


            if ($item['Color'] != null) {
                $url = $item['Color']['url'];
                $color = $item['Color']['color'];
                $productItemDTO->setColor($color);
                $productItemDTO->setUrl($url);
            }


            if ($item['Qty'] != null) {
                $qty = $item['Qty'];
                $productItemDTO->setQty($qty);
            }

            if ($item['Images'] != null) {
                foreach ($item['Images'] as $image) {
                    $uploadedFileUrl = $image->getRealPath();
                    $images[] = $uploadedFileUrl;
                }
                $productItemDTO->setImages($images);
            }

            return $this->productsService->add($productDTO, $productItemDTO);
        }
    }
}
