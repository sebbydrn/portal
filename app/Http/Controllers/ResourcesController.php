<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    public function video_guides() {
        $contacts = $this->contacts();

        return view('resources.video_guides')->with(compact('contacts'));
    }
}
