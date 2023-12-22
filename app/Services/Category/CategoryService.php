<?php

namespace App\Services\Category;

use App\DTOs\Responses\CategoryDTO;
use App\Models\ProductCategory;

class CategoryService
{
    private $true = "Successful";
    private $false = "Failed";

    public function index()
    {
        $categories = ProductCategory::get();
        //return  $categories;
        foreach ($categories as $category) {
            $categoryDTO = new CategoryDTO(
                $category->id,
                $category->category_name,
                $category->category_slug
            );
            if (!isset($category->categories)) {
                $categoryDTO->children = [];
            } else {
                $categoryDTO->children = $this->getChildren($category->categories);
            }
            $categoryDTOs[] = $categoryDTO->toArray();
        }

        return $categoryDTOs;
    }

    public function getChildren($categories)
    {
        $categoriesDTOs = [];
        foreach ($categories as $category) {
            $categoryDTO = new CategoryDTO(
                $category->id,
                $category->category_name,
                $category->category_slug,
            );
            $categoriesDTOs[] = $categoryDTO->toArray();
        }
        return $categoriesDTOs;
    }

    public function add($parentCategoryId, $categoryName)
    {
        // Check if parent category exists
        $parentCategory = ProductCategory::find($parentCategoryId);
        if (!$parentCategory) {
            return $this->false;
        }

        $category = new ProductCategory();
        $category->parent_category_id = $parentCategoryId;
        $category->category_name = $categoryName;
        $category->category_slug = $this->slugify($categoryName);
        $category->save();

        return $this->true;
    }

    public function slugify($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'null';
        }
        return $text;
    }

    public function update($categoryId, $categoryName)
    {
        $category = ProductCategory::find($categoryId);
        if (!$category) {
            return $this->false;
        }
        $category->category_name = $categoryName;
        $category->category_slug = $this->slugify($categoryName);
        $category->save();
        return $this->true;
    }

    public function delete($categoryId)
    {
        $category = ProductCategory::find($categoryId);
        if (!$category) {
            return $this->false;
        }
        $category->delete();
        return $this->true;
    }
}
