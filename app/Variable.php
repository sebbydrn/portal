<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Variable extends Model
{	
	/* lib_variables table eloquent */

    protected $connection = "seed_grow";
    // Table name
    protected $table = "lib_variables";
    // Primary key
    protected $primaryKey = "variable_id";
    // Fillable columns in lib_variables table
    protected $fillable = ["name", "variable_name", "value"];
    // Disabled timestamps in insert
    public $timestamps = false;


	// Get variables
    function variables() {
    	$variables = DB::connection('seed_grow')
        ->table('lib_variables')
    	->select('*')
    	->get();

    	return $variables;
    }

    // Get variable value
    function variable($variable_name) {
    	$value = DB::connection('seed_grow')
        ->table('lib_variables')
    	->where('variable_name', $variable_name)
    	->first();

    	return $value;
    }

    // Get variable for editing
    function variable_edit($id) {
    	$variable = DB::table('lib_variables')
    	->select('*')
    	->where('variable_id', $id)
    	->first();

    	return $variable;
    }
}
