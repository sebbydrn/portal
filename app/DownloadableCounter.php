<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadableCounter extends Model
{
    protected $connection = "cms";
	protected $primaryKey = "id";
	protected $table = "downloadables_counter";
	protected $fillable = ['downloadable_id', 'version', 'link','count'];

	public $timestamps = false;
}
