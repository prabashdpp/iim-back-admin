<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWithdrawalDetails extends Model
{
    protected $fillable = [
        'user_id',
        'account_name',
        'account_number',
        'bank_name',
        'sort_code'
    ];
}
