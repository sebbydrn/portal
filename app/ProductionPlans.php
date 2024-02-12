<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionPlans extends Model
{
    protected $connection = "seed_production_planner";
    protected $table = "production_plans";
}
