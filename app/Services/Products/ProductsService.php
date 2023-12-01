<?php

namespace App\Services\Products;

use PDO;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\ProductCategory;
use function PHPSTORM_META\map;
use App\Models\ProductItemImage;
use Illuminate\Support\Facades\DB;
use App\DTOs\Responses\ProductsDTO\ProductDTO;

use Illuminate\Pagination\LengthAwarePaginator;
use App\DTOs\Responses\ProductsDTO\ItemImageDTO;
use App\DTOs\Responses\ProductsDTO\ProductItemDTO;
use App\DTOs\Responses\ProductsDTO\SearchProductDTO;

class ProductsService
{
    private $true = "Successfull";
    private $false = "Failed";
    public function products($category_slug)
    {

        $categoryId = ProductCategory::where('category_slug', $category_slug)->first()->id;

        $products = Product::where('category_id', $categoryId)->get();

        $productsDTO = [];

        foreach ($products as $product) {
            $productDTO = new ProductDTO(
                $product->id,
                $product->category_id,
                $product->name,
                $product->description,
                $product->price_int,
                $product->price_str,
                ProductCategory::where('id', $categoryId)->first()->category_name,
                //$product->product_items
            );

            ProductItem::where('product_id', $product->id)->get()
                ->map(function ($productItem) use ($productDTO) {

                    $itemImages = [];

                    $images = ProductItemImage::where('product_item_id', $productItem->id)->get();
                    foreach ($images as $image) {
                        $itemImage = new ItemImageDTO(
                            $image->id,
                            $image->product_item_id,
                            $image->url,
                        );
                        $itemImages[] = $itemImage->toArray();
                    }

                    $productItemDTO = new ProductItemDTO(
                        $productItem->id,
                        $productItem->product_id,
                        $productItem->size,
                        $productItem->color,
                        $productItem->color_image,
                        $productItem->qty_in_stock,
                        null,
                        null,
                        $itemImages
                    );
                    $productDTO->productItems[] =  $productItemDTO->toArray();
                });

            $productsDTO[] = $productDTO->toArray();
        }


        // The number of items per page
        $perPage = 10; // You can change this value to the number of items you want per page.

        // Create a LengthAwarePaginator instance
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');

        $pagedData = array_slice($productsDTO, ($page - 1) * $perPage, $perPage);

        $casualPaginated = new LengthAwarePaginator(
            $pagedData,
            count($productsDTO),
            $perPage,
            null,
            ['path' => 'localhost:8000/api/product/' . $category_slug]
        );

        return response($casualPaginated);
    }

    public function show($productId)
    {
        $product = Product::where('id', $productId)->first();
        $productDTO = new ProductDTO(
            $product->id,
            $product->category_id,
            $product->name,
            $product->description,
            $product->price_int,
            $product->price_str,
            ProductCategory::where('id', $product->category_id)->first()->category_name,
            //$product->product_items
        );
        ProductItem::where('product_id', $product->id)->get()
            ->map(function ($productItem) use ($productDTO) {

                $itemImages = [];

                $images = ProductItemImage::where('product_item_id', $productItem->id)->get();
                foreach ($images as $image) {
                    $itemImage = new ItemImageDTO(
                        $image->id,
                        $image->product_item_id,
                        $image->url,
                    );
                    $itemImages[] = $itemImage->toArray();
                }

                $productItemDTO = new ProductItemDTO(
                    $productItem->id,
                    $productItem->product_id,
                    $productItem->size,
                    $productItem->color,
                    $productItem->color_image,
                    $productItem->qty_in_stock,
                    null,
                    null,
                    $itemImages
                );
                $productDTO->productItems[] =  $productItemDTO->toArray();
            });

        return response($productDTO->toArray());
    }

    public function search($searchTerm)
    {
        $products = Product::where('name', 'like', '%' . $searchTerm . '%')->get();

        $productsDTO = [];

        foreach ($products as $product) {

            $img = $product->product_items->first()->product_item_images->first()->url ?? null;

            $productDTO = new SearchProductDTO(
                $product->id,
                $product->category_id,
                $product->name,
                $product->description,
                $product->price_int,
                $product->price_str,
                ProductCategory::where('id', $product->category_id)->first()->category_name,
                $img
            );

            $productsDTO[] = $productDTO->toArray();
        }
        return response($productsDTO);
    }

    public function update($updateProductDTO)
    {
        $product = Product::where('id', $updateProductDTO->id)->first();
        $product->name = $updateProductDTO->name;
        $product->category_id = $updateProductDTO->categoryId;
        $product->description = $updateProductDTO->description;
        $product->price_int = $updateProductDTO->priceInt;
        $product->price_str = $updateProductDTO->priceStr;
        $product->save();

        return response($this->true);
    }

    public function add(){
        return $this->true;
    }
}
