<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;

class SiteMapController extends Controller {
    
	public function index() {
		$contacts = $this->contacts();

		$page_model = new Page();

		$pages = $page_model->pages();
		$sections = $page_model->sections();

		return view('sitemap.index')
		->with(compact('contacts'))
		->with(compact('pages'))
		->with(compact('sections'));
	}

}
