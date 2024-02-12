<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Content extends Model {
    
	public function objectives() {
		$objectives = DB::connection('cms')
		->table('contents')
		->select('content')
		->where('subtitle', 'OBJECTIVES')
		->where('is_published', 1)
		->first();

		return $objectives;
	}

	public function mission() {
		$mission = DB::connection('cms')
		->table('contents')
		->select('content')
		->where('subtitle', 'MISSION')
		->where('is_published', 1)
		->first();

		return $mission;
	}

	public function vision() {
		$vision = DB::connection('cms')
		->table('contents')
		->select('content')
		->where('subtitle', 'VISION')
		->where('is_published', 1)
		->first();

		return $vision;
	}

	public function content($section) {
		$content = DB::connection('cms')
		->table('contents')
		->leftJoin('sections', 'sections.section_id', '=', 'contents.section_id')
		->select('contents.*')
		->where('sections.display_name', $section)
		->where('contents.is_published', 1)
		->orderBy('contents.content_id', 'asc')
		->get();

		return $content;
	}

	public function content_page($page) {
		$content = DB::connection('cms')
		->table('contents')
		->leftJoin('pages', 'pages.page_id', '=', 'contents.page_id')
		->select('contents.*')
		->where('pages.display_name', $page)
		->where('contents.is_published', 1)
		->orderBy('contents.content_id', 'asc')
		->get();

		return $content;
	}

}
