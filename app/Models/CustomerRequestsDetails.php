<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRequestsDetails extends Model
{
    protected $fillable = [
        'request_id','description','message','user_id','result_status'
    ];
}
