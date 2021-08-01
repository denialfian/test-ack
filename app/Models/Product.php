<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'harga',
        'stok',
        'nomor_rak',
        'toko_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['photos', 'photo_main'];

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function photo_main()
    {
        return $this->hasOne(ProductPhoto::class)->where('is_main', 1);
    }
}
