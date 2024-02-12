<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CSEstimatedYieldData extends Model
{
    protected $connection = "dashboard";
    protected $table = "cs_estimated_yield_data";
}
