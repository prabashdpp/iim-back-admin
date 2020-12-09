<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable =  [
        'user_id', 'amount','currency','status','gr_id','stripe_customer_id'
    ];
}
