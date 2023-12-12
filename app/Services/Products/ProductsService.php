<?php

namespace App\Services\Products;

use App\DTOs\Requests\AddProduct\AddProductDTO;
use App\DTOs\Requests\AddProduct\AddProductItemDTO;
use PDO;
use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use function PHPSTORM_META\map;
use App\Models\ProductItemImage;

use Illuminate\Support\Facades\DB;
use App\DTOs\Responses\ProductsDTO\ProductDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use App\DTOs\Responses\ProductsDTO\ItemImageDTO;
use App\DTOs\Responses\ProductsDTO\ProductItemDTO;
use App\DTOs\Responses\ProductsDTO\SearchProductDTO;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductsService
{
    private $true = "Successfull";
    private $false = "Failed";
    public function products($category_slug)
    {

        $categoryId = ProductCategory::where('category_slug', $category_slug)->first()->id;
        // list all children of category
        $children = ProductCategory::where('parent_category_id', $categoryId)->get();

        $products = Product::where('category_id', $categoryId)
            ->orWhere(function ($query) use ($children) {
                foreach ($children as $child) {
                    $query->orWhere('category_id', $child->id);
                }
            })
            ->get();


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

            // get all the color of this product
            $colorList = ProductItem::where('product_id',   $product->id)
                ->distinct('color')
                ->pluck('color');

            // get all the size and corresponding item_id of each color if qty > 0
            foreach ($colorList as $color) {

                $sizesAndIds = ProductItem::where('color', $color)
                    ->where('product_id',  $product->id)
                    ->where('qty_in_stock', '>', 0)
                    ->select('size', DB::raw('MIN(id) as id'))
                    ->groupby('size')
                    ->get();

                $sizes = [];
                $itemIds = [];

                foreach ($sizesAndIds as $sizeAndId) {
                    $sizes[] = $sizeAndId->size;
                    $itemIds[] = $sizeAndId->id;
                }

              


                $item = ProductItem::where('id', $itemIds[0])
                    ->first();

                $itemImages = ProductItemImage::where('product_item_id', $item->id)->get();
                $images = [];
                foreach($itemImages as $itemImage){
                    $images[] = $itemImage->url;
                }
                $productItemDTO = new ProductItemDTO(
                    $item->id,
                    $item->product_id,
                    $item->size,
                    $sizes,
                    $itemIds,
                    $item->color,
                    $item->color_image,
                    $item->qty_in_stock,
                    null,
                    null,
                    $images
                );
                $productDTO->productItems[] =  $productItemDTO->toArray();
            }

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

    public function add($productDTO, $productItemDTO)
    {
        //return $request;

        $product = Product::create([
            'category_id' => $productDTO->getCategoryId(),
            'name' => $productDTO->getName(),
            'description' => $productDTO->getDescription(),
            'price_int' => $productDTO->getPriceInt(),
            'price_str' => $productDTO->getPriceStr(),
        ]);

        $productId = $product->id;
        foreach ($productItemDTO->getSize() as $size) {
            $productItem = ProductItem::create([
                'product_id' => $productId,
                'size' => $size,
                'color' => $productItemDTO->getColor(),
                'color_image' => $productItemDTO->getUrl(),
                'qty_in_stock' => $productItemDTO->getQty(),
            ]);

            $productItemId = $productItem->id;

            foreach ($productItemDTO->getImages() as $image) {
                $link = Cloudinary::upload($image)->getSecurePath();
                ProductItemImage::create([
                    'product_item_id' => $productItemId,
                    'url' => $link,
                ]);
            }
        }

        return $this->show($productId);
    }
}
