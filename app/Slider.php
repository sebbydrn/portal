<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Slider extends Model {

	public function sliders() {
    	$sliders = DB::connection('cms')->table('sliders')->get();
    	return $sliders;
    }

}
