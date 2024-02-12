<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseActivities extends Model {
    
    protected $connection = "warehouse";
	protected $primaryKey = "warehouse_activity_id";
	protected $fillable = [
		'warehouse_id', 
		'user_id', 
		'activity', 
		'browser', 
		'device', 
		'ip_env_address', 
		'ip_server_address',
		'new_value',
		'old_value',
		'OS'
	];
	
	public $timestamps = false;

	// Get the warehouse that owns the activity
	public function warehouse() {
		return $this->belongsTo('App\Warehouse', 'warehouse_id', 'warehouse_id');
	}

}
