<?php

namespace App\Sales;

use Illuminate\Database\Eloquent\Model;

class SeedSales extends Model
{   
    protected $connection = "dashboard";
    protected $table = "seed_sales";
    protected $primaryKey = "seed_sales_id";
    protected $fillable = ['total_volume_sold', 'fs_volume_sold', 'rs_volume_sold', 'transactions', 'ave_transactions_day', 'year', 'sem'];

    public $timestamps = false;
}
