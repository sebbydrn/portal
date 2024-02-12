<?php

namespace App\Sales;

use Illuminate\Database\Eloquent\Model;

class VarietiesSold extends Model
{   
    protected $connection = "dashboard";
    protected $table = "varieties_sold";
    protected $primaryKey = "varieties_sold_id";
    protected $fillable = ['variety', 'quantity', 'year', 'sem'];

    public $timestamps = false;

}
