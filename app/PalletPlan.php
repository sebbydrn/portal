<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PalletPlan extends Model {
    
    protected $connection = "warehouse";
	protected $primaryKey = "pallet_plan_id";
	protected $fillable = [
		'warehouse_id', 
		'year', 
		'semester', 
		'description', 
		'status',
		'warehouse_length',
		'warehouse_width',
		'pallet_width',
		'pallet_length',
		'distance_wall',
		'forklift_road_width',
		'warehouseman_road_width',
		'rows',
		'columns'
	];

	public $timestamps = false;

	// Get the activities for the pallet plan
	public function activities() {
		return $this->hasMany('App\PalletPlanActivities', 'pallet_plan_id', 'pallet_plan_id');
	}

	// Get the warehouse that owns the pallet plan
	public function warehouses() {
		return $this->belongsTo('App\Warehouse', 'warehouse_id', 'warehouse_id');
	}

}
