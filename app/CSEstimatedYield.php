<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CSEstimatedYield extends Model
{
    protected $connection = "dashboard";
    protected $table = "cs_estimated_yield";
}
