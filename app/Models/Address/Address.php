<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'is_default',
        'receiver_name',
        'receiver_phone',
        'city_id',
        'district',
        'postal_code',
        'detail_address',
        'address_note',
        'type',
    ];
}
