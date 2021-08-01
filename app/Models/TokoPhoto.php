<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokoPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'is_main',
        'toko_id',
    ];

    protected $appends = [
        'photo_src',
    ];

    public function getPhotoSrcAttribute()
    {
        return $this->file_name == null ? '' : asset('storage/uploads/toko/photos/' . $this->file_name);
    }
}
