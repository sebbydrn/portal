<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CSProductionData extends Model
{
    protected $connection = "dashboard";
    protected $table = "cs_production_data";
}