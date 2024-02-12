<?php

namespace App\Sales;

use Illuminate\Database\Eloquent\Model;

class FSSalesStation extends Model
{   
    protected $connection = "dashboard";
    protected $table = "fs_sales_station";
    protected $primaryKey = "fs_sales_station_id";
    protected $fillable = ['variety', 'quantity', 'year', 'sem'];

    public $timestamps = false;
}
