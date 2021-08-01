<?php

namespace App\Service;

use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\Toko;
use App\Models\TokoPhoto;
use Illuminate\Http\Request;

class ProductService
{
    private $max_photo_upload = 100;

    public function create(Request $request)
    {
        $user = $this->getUser();

        // check user has toko
        $this->hasTokoCheck();

        // check toko owner
        $this->tokoOnwnerCheck($request->toko_id);

        // check max product
        $this->tokoMaxProductCheck($request->toko_id);

        // create product
        $product = Product::create([
            'name' => $request->name,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'nomor_rak' => $request->nomor_rak,
            'toko_id' => $request->toko_id,
        ]);

        // upload photo
        $this->uploadPhoto($product, $request);

        return $product;
    }

    public function update($id, Request $request)
    {
        // check toko owner
        $this->tokoOnwnerCheck($request->toko_id);

        // check toko owner
        $product = $this->productOwnerCheck($id);

        if ($product->toko_id != $request->toko_id) {
            // check max product
            $this->tokoMaxProductCheck($request->toko_id, $id);
        }

        $product = Product::where('id', $id)->where('toko_id', $product->toko_id)->firstOrFail();

        $product->update([
            'name' => $request->name,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'nomor_rak' => $request->nomor_rak,
            'toko_id' => $request->toko_id,
        ]);

        return $product;
    }

    public function tokoOnwnerCheck($toko_id)
    {
        $user = $this->getUser();

        $userToko = Toko::where('user_id', $user->id)->where('id', $toko_id)->first();
        if ($userToko == null) {
            throw new \Exception('userToko not found');
        }

        return $userToko;
    }

    public function productOwnerCheck($product_id)
    {
        $user = $this->getUser();

        $product = Product::where('id', $product_id)->whereHas('toko', function ($qusery) use ($user) {
            $qusery->where('user_id', $user->id);
        })->first();

        if ($product == null) {
            throw new \Exception('product not found');
        }

        return $product;
    }

    public function hasTokoCheck()
    {
        $user = $this->getUser();

        $userTokoCount = Toko::where('user_id', $user->id)->count();
        if ($userTokoCount == 0) {
            throw new \Exception('please create toko first');
        }
    }

    public function tokoMaxProductCheck($toko_id, $product_id = null)
    {
        $count = Product::where('toko_id', $toko_id)->count();

        if ($product_id != null) {
            $count = Product::where('toko_id', $toko_id)->where('id', '!=', $product_id)->count();
        }

        if ($count >= 100) {
            throw new \Exception('error max product 100');
        }

        return $count;
    }

    public function uploadPhoto(Product $product, Request $request)
    {
        $photoData = [];
        if ($request->hasfile('product_photo')) {
            $photoUpload = $request->file('product_photo');
            $countPhoto = $this->tokoMaxProductCheck($request->toko_id) + count($photoUpload);
            if ($countPhoto > $this->max_photo_upload) {
                throw new \Exception('error photo max ' . $this->max_photo_upload);
            }

            $main = $product->photo_main;

            foreach ($photoUpload as $key => $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads/products/photos', $fileName, 'public');
                $mainPhoto = $key == 0 ? 1 : 0;
                $photoData[] = new ProductPhoto([
                    'is_main' => $main == null ? $mainPhoto : 0,
                    'file_name' => $fileName
                ]);
            }
        }

        if (count($photoData) > 0) {
            $product->photos()->saveMany($photoData);
        }

        return $product;
    }

    public function getUser()
    {
        $userService = new UserService;
        $user = $userService->getAuthUser();

        return $user;
    }
}
