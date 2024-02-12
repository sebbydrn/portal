<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;

class AboutUsController extends Controller {

   	public function rsis() {
   		$contacts = $this->contacts();

		$content_model = new Content();

		// Get about_us/rsis content
		$contents = $content_model->content("RSIS");

		return view('about_us.rsis')
		->with(compact('contacts'))
		->with(compact('contents'));
	}

	public function objectives() {
		$contacts = $this->contacts();

		$content_model = new Content();

		// Get about_us/objectives content
		$contents = $content_model->content("Objectives");

		return view('about_us.objectives')
		->with(compact('contacts'))
		->with(compact('contents'));
	}

	public function implementers() {
		$contacts = $this->contacts();

		$content_model = new Content();

		// Get about_us/implementers content
		$contents = $content_model->content("Implementers");

		return view('about_us.implementers')
		->with(compact('contacts'))
		->with(compact('contents'));
	}

	public function partners() {
		$contacts = $this->contacts();
		$partners = $this->partners_list();

		$content_model = new Content();

		// Get about_us/objectives content
		$contents = $content_model->content("Partners");

		return view('about_us.partners')
		->with(compact('contacts'))
		->with(compact('contents'))
		->with(compact('partners'));
		// var_dump($partners);
	}
}
