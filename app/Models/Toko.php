<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'province',
        'user_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['photos', 'photo_main'];

    public function photos()
    {
        return $this->hasMany(TokoPhoto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photo_main()
    {
        return $this->hasOne(TokoPhoto::class)->where('is_main', 1);
    }
}
