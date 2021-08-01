<?php

namespace App\Service;

use App\Models\Toko;
use App\Models\TokoPhoto;
use Illuminate\Http\Request;

class TokoService
{
    private $max_toko = 3;
    private $max_photo_upload = 3;

    public function create(Request $request)
    {
        $user = $this->getUser();

        $this->maxCreateCheck();

        $toko = Toko::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'user_id' => $user->id,
        ]);

        $this->uploadPhoto($toko, $request);

        return $toko;
    }

    public function update($id, Request $request)
    {
        $user = $this->getUser();

        $toko = Toko::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $toko->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
        ]);

        return $toko;
    }

    public function uploadPhoto(Toko $toko, Request $request)
    {
        $tokoPhotoData = [];
        if ($request->hasfile('toko_photo')) {
            $tokoPhohoUpload = $request->file('toko_photo');
            $tokoPhohoUploadCount = $this->maxPhotoCheck($toko->id) + count($tokoPhohoUpload);
            if ($tokoPhohoUploadCount > $this->max_photo_upload) {
                throw new \Exception('error photo max 3');
            }

            $main = $toko->photo_main;

            foreach ($tokoPhohoUpload as $key => $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads/toko/photos', $fileName, 'public');
                $mainPhoto = $key == 0 ? 1 : 0;
                $tokoPhotoData[] = new TokoPhoto([
                    'is_main' => $main == null ? $mainPhoto : 0,
                    'file_name' => $fileName
                ]);
            }
        }

        if (count($tokoPhotoData) > 0) {
            $toko->photos()->saveMany($tokoPhotoData);
        }

        return $toko;
    }

    public function maxCreateCheck()
    {
        $user = $this->getUser();

        $userTokoCount = Toko::where('user_id', $user->id)->count();
        if ($userTokoCount >= $this->max_toko) {
            throw new \Exception('error toko max 3');
        }

        return $userTokoCount;
    }

    public function maxPhotoCheck($toko_id)
    {
        $count = TokoPhoto::where('toko_id', $toko_id)->count();
        if ($count >= $this->max_photo_upload) {
            throw new \Exception('error photo max 3');
        }

        return $count;
    }

    public function getUser()
    {
        $userService = new UserService;
        $user = $userService->getAuthUser();

        return $user;
    }
}
