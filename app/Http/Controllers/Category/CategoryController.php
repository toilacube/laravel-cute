<?php
namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    public function __construct(private CategoryService $categoryService)
    {
    }
    
    public function index(){
        return $this->categoryService->index();
    }

    public function add(Request $request){
        $parentCategoryId = $request->parentCategoryId;
        $categoryName = $request->categoryName;
        return $this->categoryService->add($parentCategoryId, $categoryName);
    }

    public function update(Request $request){
        $categoryId = $request->id;
        $categoryName = $request->categoryName;
        return $this->categoryService->update($categoryId, $categoryName);
    }

    public function delete(Request $request){
        $categoryId = $request->getContent();
        return $this->categoryService->delete($categoryId);
    }
}