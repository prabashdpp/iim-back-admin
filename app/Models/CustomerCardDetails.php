<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCardDetails extends Model
{
        protected $table = 'customer_card_details';

        
    protected $fillable = [
     'user_id', 'token_id', 'card_id','exp_month','exp_year','last_digits','country','brand','stripe_customer_id','fingerprint'
    ];
}
   