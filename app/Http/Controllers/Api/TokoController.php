<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\TokoPhotoRequest;
use App\Http\Requests\TokoRequest;
use App\Models\Toko;
use App\Service\TokoService;

class TokoController extends ApiController
{
    // input toko
    public function store(TokoRequest $request, TokoService $tokoService)
    {
        return $this->successResponse($tokoService->create($request), 'ok');
    }

    // input toko poto
    public function storePhoto(TokoPhotoRequest $request, $toko_id, TokoService $tokoService)
    {
        $toko = Toko::where('id', $toko_id)->where('user_id', $tokoService->getUser()->id)->firstOrFail();

        return $this->successResponse($tokoService->uploadPhoto($toko, $request), 'ok');
    }

    // update toko
    public function update(TokoRequest $request, $id, TokoService $tokoService)
    {
        return $this->successResponse($tokoService->update($id, $request), 'ok');
    }

    // get one toko
    public function show($id)
    {
        $toko = Toko::with(['user:id,avatar,name'])->where('id', $id)->firstOrFail();

        return $this->successResponse($toko, 'ok');
    }

    // get all toko
    public function showAll()
    {
        $tokos = Toko::with(['user:id,avatar,name'])->orderBy('name', 'asc')->paginate(10);

        return $this->successResponse($tokos, 'ok');
    }

    // get all toko by user id
    public function showAllByUser($user_id)
    {
        $tokos = Toko::with(['user:id,avatar,name'])->where('user_id', $user_id)->orderBy('name', 'asc')->get();

        return $this->successResponse($tokos, 'ok');
    }
}
