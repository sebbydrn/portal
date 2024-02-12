<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Partner extends Model {

	public function partners() {
    	$partners = DB::connection('cms')
        ->table('partners')
        ->orderBy('partner_id', 'asc')
        ->get();
        
    	return $partners;
    }

}
