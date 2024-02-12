<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AutoResponse extends Model {

	protected $connection = 'cms';

	protected $table = 'auto_response';

    protected $primaryKey = 'auto_response_id';

	protected $fillable = ['sender', 'title', 'body', 'is_enabled'];

	public $timestamps = false;

    public function auto_response() {
    	$data = DB::connection('cms')
    	->table('auto_response')
    	->select('*')
    	->where('is_enabled', 1)
    	->first();

    	return $data;
    }

}
