<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersLogs extends Model
{
      protected $fillable = ['user_id', 'description','log_type', 'reference'];
}
