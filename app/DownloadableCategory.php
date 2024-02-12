<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadableCategory extends Model {
    
	protected $connection = "cms";
	protected $primaryKey = "downloadable_category_id";
	protected $table = "downloadable_categories";
	protected $fillable = ['display_name', 'is_public', 'is_published'];

	public $timestamps = false;
    
}
