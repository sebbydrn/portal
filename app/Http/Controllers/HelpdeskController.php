<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpdeskController extends Controller {
    
	public function index() {
		$contacts = $this->contacts();

		return view('helpdesk.index')
		->with(compact('contacts'));
	}

}
