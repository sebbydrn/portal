<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Page extends Model {

	protected $connection = 'cms';

	protected $table = 'pages';

    protected $primaryKey = 'page_id';

	protected $fillable = ['display_name', 'url', 'is_public', 'is_published'];

	public $timestamps = false;

	public function pages() {
    	$pages = DB::connection('cms')
        ->table('pages')
        ->where('is_published', 1)
        ->orderBy('page_id', 'asc')
        ->get();

    	return $pages;
    }

    public function sections() {
        $sections = DB::connection('cms')
        ->table('sections')
        ->where('is_published', 1)
        ->orderBy('section_id', 'asc')
        ->get();
        
        return $sections;
    }
}
