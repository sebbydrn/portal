<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PendingRegistration extends Model
{
    // Check activate/add password link if already used
    function password_link($link) {
        $status = DB::table('password_links')
        ->select('is_done')
        ->where('link', $link)
        ->first();

        return $status;
    }

    // Get userid
    function get_user_id($id) {
        $data = DB::table('password_links')
        ->select('user_id')
        ->where('link', $id)
        ->first();

        return $data;
    }
}
