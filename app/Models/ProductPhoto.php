<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'is_main',
        'product_id',
    ];

    protected $appends = [
        'photo_src',
    ];

    public function getPhotoSrcAttribute()
    {
        return $this->file_name == null ? '' : asset('storage/uploads/products/photos/' . $this->file_name);
    }
}
