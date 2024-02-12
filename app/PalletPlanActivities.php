<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PalletPlanActivities extends Model {
    
    protected $connection = "warehouse";
	protected $primaryKey = "pallet_plan_activity_id";
	protected $fillable = [
		'pallet_plan_id', 
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

	// Get the pallet plan that owns the activity
	public function pallet_plan() {
		return $this->belongsTo('App\PalletPlan', 'pallet_plan_id', 'pallet_plan_id');
	}

}
