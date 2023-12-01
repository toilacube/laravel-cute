<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DTOs\Requests\AddProductDTO;
use App\Http\Controllers\Controller;
use App\DTOs\Requests\UpdateProductDTO;
use App\Services\Products\ProductsService;
use Illuminate\Database\Eloquent\Casts\Json;

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
        return  $this->productsService->products($request->category_slug);
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
        return $request->all();
        $items = $request->file('list');
        $images = [];
        foreach ($items as $item) {
           
            $images[] = $item->getRealPath();
        }
        return $images;


        return 'False';

        return $this->productsService->add();
    }
}
