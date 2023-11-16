<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Products\ProductsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Json;

class ProductsController extends Controller
{
    public function __construct(private ProductsService $productsService)
    {
    }
    /**
     * Display MAC HANG NGAY(casual) products.
     */
    public function casual(Request $request)
    {
        return  $this->productsService->getCasualProducts($request->query('category'));
    }

    public function test()
    {
        return $this->productsService->getTest();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
