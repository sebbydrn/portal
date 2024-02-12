<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Inquiry extends Model {
    
	protected $connection = 'cms';

	protected $table = 'inquiries';

    protected $primaryKey = 'inquiry_id';

	protected $fillable = ['sender', 'email', 'inquiry', 'status'];

	public $timestamps = false;

	public function add_log($log) {
    	DB::connection('cms')->table('inquiry_activities')->insert($log);
    }

    public function add_inquiry_receiver_log($log) {
    	DB::connection('cms')->table('inquiry_receiver')->insert($log);
    }

    public function add_inquiry_autores_log($log) {
        DB::connection('cms')->table('inquiry_autores')->insert($log);
    }
}
