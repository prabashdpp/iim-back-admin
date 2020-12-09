<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRequests extends Model
{
    protected $fillable = [
        'item_id', 'amount', 'user_id', 'type','request'
    ];
}
