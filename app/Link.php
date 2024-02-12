<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Link extends Model {
   	
	public function links() {
    	$links = DB::connection('cms')->table('links')->get();
    	return $links;
    }

}
