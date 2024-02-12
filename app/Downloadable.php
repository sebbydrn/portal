<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Downloadable extends Model {
    
	protected $connection = "cms";
	protected $primaryKey = "downloadable_id";
	protected $table = "downloadables";
	protected $fillable = ['display_name', 'link', 'downloadable_category_id', 'is_public', 'is_published'];

	public $timestamps = false;
    
}
