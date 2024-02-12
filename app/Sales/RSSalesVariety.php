<?php

namespace App\Sales;

use Illuminate\Database\Eloquent\Model;

class RSSalesVariety extends Model
{   
    protected $connection = "dashboard";
    protected $table = "rs_sales_variety";
    protected $primaryKey = "rs_sales_variety_id";
    protected $fillable = ['variety', 'quantity', 'year', 'sem'];

    public $timestamps = false;
}
