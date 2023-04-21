<?php

namespace App\Service;

use App\Repository\CategoryRepository;


class NavCategory
{
    private $categoryRespository;

    public function __construct(CategoryRepository $categoryRespository)
    {
        $this->categoryRepository = $categoryRespository;
    }

    public function category():array
    {
        return $this->categoryRepository->findAll();
    }
}