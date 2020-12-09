<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseActions extends Model
{
      protected $fillable = [
        'item_id', 'amount', 'post_amount', 'action','worth_value','trade_value','commission','user_id'
    ];
}


