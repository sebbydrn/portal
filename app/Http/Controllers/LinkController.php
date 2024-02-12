<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Link;

class LinkController extends Controller {

	public function index() {
		$contacts = $this->contacts();

		// Get data
		$link_model = new Link();
        $links = $link_model->links();

		return view('links.index')
		->with(compact('contacts'))
		->with(compact('links'));
	}

}
