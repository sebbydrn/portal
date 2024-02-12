<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionEstimate extends Model
{
    protected $connection = "dashboard";
    protected $table = "production_estimates";
}
