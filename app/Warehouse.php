<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model {
    
    protected $connection = "warehouse";
	protected $primaryKey = "warehouse_id";
	protected $fillable = ['name', 'station_id', 'length', 'width', 'height'];

	public $timestamps = false;

	// Get the activities for the warehouse
	public function activities() {
		return $this->hasMany('App\WarehouseActivities', 'warehouse_id', 'warehouse_id');
	}
}
