<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class System extends Model
{	
	protected $primaryKey = 'system_id';

	protected $fillable = ['name', 'display_name', 'description'];

	public $timestamps = false;

    public function systems() {
    	$systems = DB::table('systems')->orderBy('display_name', 'asc')->get();
    	return $systems;
    }
}
