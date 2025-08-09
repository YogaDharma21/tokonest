<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'province_id',
        'external_id',
        'name',
    ];
}
