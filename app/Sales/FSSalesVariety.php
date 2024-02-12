<?php

namespace App\Sales;

use Illuminate\Database\Eloquent\Model;

class FSSalesVariety extends Model
{   
    protected $connection = "dashboard";
    protected $table = "fs_sales_variety";
    protected $primaryKey = "fs_sales_variety_id";
    protected $fillable = ['variety', 'quantity', 'year', 'sem'];

    public $timestamps = false;
}
