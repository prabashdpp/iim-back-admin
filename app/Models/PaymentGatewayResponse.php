<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayResponse extends Model
{
    protected $fillable = [
        'user_id', 'response'
    ];

}
