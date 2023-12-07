<?php
namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DTOs\Requests\ProductItemReqDTO;
use App\Services\Products\ProductItemService;

class ProductItemController extends Controller{
    public function __construct(private ProductItemService $productItemService)
    {
    }
   
    public function show(Request $request)
    {
        $itemId = $request->id;
        return $this->productItemService->show($itemId);
    }

    public function updateQtyInStock(Request $request)
    {
        $itemId = $request->id;
        $qtyInStock = $request->qtyInStock;
        return $this->productItemService->updateQtyInStock($itemId, $qtyInStock);
    }

    public function add(Request $request)
    {


        $productItemDTO = new ProductItemReqDTO(
            $request->ProductId,
            $request->Size,
            $request->Color['color'],
            $request->Color['url'],
            $request->Qty,
            $request->Images,
        );
        return $this->productItemService->add($productItemDTO);
    }

    public function delete(Request $request){
        $listId = $request->getContent();
        return $this->productItemService->delete($listId);
    }
}