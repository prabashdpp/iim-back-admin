<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWallet extends Model {

    //


    protected $fillable = [
        'user_id', 'gold_amount', 'cash_amount', 'currency'
    ];

}
