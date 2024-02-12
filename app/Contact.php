<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contact extends Model {

	public function contacts() {
    	$contacts = DB::connection('cms')->table('contacts')->get();
    	return $contacts;
    }
    
}
