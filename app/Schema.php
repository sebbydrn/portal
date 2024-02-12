<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schema extends Model {
    
    protected $connection = "warehouse";
	protected $primaryKey = "schema_id";
	protected $fillable = [
		'schema_id', 
		'name', 
		'station_id',
	];

	public $timestamps = false;

	// Get the activities for the schema
	public function activities() {
		return $this->hasMany('App\SchemaActivities', 'schema_id', 'schema_id');
	}
	

}
