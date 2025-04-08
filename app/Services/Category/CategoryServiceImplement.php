<?php

namespace App\Services\Category;

use LaravelEasyRepository\Service;
use App\Repositories\Category\CategoryRepository;

class CategoryServiceImplement extends Service implements CategoryService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(CategoryRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
