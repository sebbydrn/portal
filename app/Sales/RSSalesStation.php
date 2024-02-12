<?php

namespace App\Sales;

use Illuminate\Database\Eloquent\Model;

class RSSalesStation extends Model
{   
    protected $connection = "dashboard";
    protected $table = "rs_sales_station";
    protected $primaryKey = "rs_sales_station_id";
    protected $fillable = ['variety', 'quantity', 'year', 'sem'];

    public $timestamps = false;
}
