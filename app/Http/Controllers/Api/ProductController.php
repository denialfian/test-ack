<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductPhotoRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Toko;
use App\Service\ProductService;
use App\Service\UserService;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    // input product
    public function store(ProductRequest $request, ProductService $productService)
    {
        return $this->successResponse($productService->create($request), 'ok');
    }

    // input product poto
    public function storePhoto(ProductPhotoRequest $request, $product_id, ProductService $productService)
    {
        $product = $productService->productOwnerCheck($product_id);

        return $this->successResponse($productService->uploadPhoto($product, $request), 'ok');
    }

    // update data
    public function update(ProductRequest $request, $id, ProductService $productService)
    {
        return $this->successResponse($productService->update($id, $request), 'ok');
    }

    public function show($id)
    {
        $product = Product::with('toko')->where('id', $id)->firstOrFail();

        return $this->successResponse($product, 'ok');
    }

    public function showAll()
    {
        $products = Product::with('toko')->orderBy('name', 'asc')->paginate(10);

        return $this->successResponse($products, 'ok');
    }

    public function showAllByToko($toko_id)
    {
        $products = Product::with('toko')->where('toko_id', $toko_id)->orderBy('name', 'asc')->paginate(10);

        return $this->successResponse($products, 'ok');
    }
}
