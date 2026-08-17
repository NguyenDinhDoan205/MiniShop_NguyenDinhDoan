<?php

namespace Controllers\Client;

use DAO\ProductDAO;

class ProductController
{
    private ProductDAO $productDAO;

    public function __construct()
    {
        $this->productDAO = new ProductDAO();
    }

    public function index()
    {
        echo "Client ProductController hoạt động!";
    }
}