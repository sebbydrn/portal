<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Passwords extends Model
{
    protected $primaryKey = 'password_id';

    protected $fillable = ['password', 'user_id', 'system_id'];

    public $timestamps = false;

    public function update_password_link($link) {
    	DB::beginTransaction();
        try {
            DB::table('password_links')
            ->where('link', $link)
            ->update(['is_done' => 1]);

            DB::commit();
            return "success";
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
