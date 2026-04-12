<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'external_id',
        'name',
    ];
}
