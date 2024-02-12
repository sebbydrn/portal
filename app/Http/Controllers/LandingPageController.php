<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;

class LandingPageController extends Controller {
    
	public function index() {
		$contacts = $this->contacts();
		$sliders = $this->sliders();
		$partners = $this->partners_list();

		$content_model = new Content();

		// Get objectives
		$objectives = $content_model->objectives();

		// Get mission
		$mission = $content_model->mission();

		// Get vision
		$vision = $content_model->vision();

		return view('landing_page.index')
		->with(compact('contacts'))
		->with(compact('sliders'))
		->with(compact('partners'))
		->with(compact('objectives'))
		->with(compact('mission'))
		->with(compact('vision'));
	}


}
