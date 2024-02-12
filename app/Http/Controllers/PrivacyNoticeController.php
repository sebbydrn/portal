<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;

class PrivacyNoticeController extends Controller {

	public function index() {
		$contacts = $this->contacts();

		$content_model = new Content();

		// Get about_us/rsis content
		$contents = $content_model->content_page("Privacy Notice");

		return view('privacy_notice.index')
		->with(compact('contacts'))
		->with(compact('contents'));
	}

}
