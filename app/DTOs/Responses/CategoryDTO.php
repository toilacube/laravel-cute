<?php

namespace App\DTOs\Responses;

class CategoryDTO
{
    public $id;
    public $categoryName;
    public $slug;
    public $children;

    public function __construct($id, $categoryName, $slug)
    {
        $this->id = $id;
        $this->categoryName = $categoryName;
        $this->slug = $slug;
        // $this->children = $children;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'categoryName' => $this->categoryName,
            'slug' => $this->slug,
            'children' => $this->children,
        ];
    }
    
    
}

