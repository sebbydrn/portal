<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Receiver extends Model {
    
	protected $connection = 'cms';

	protected $table = 'receivers';

    protected $primaryKey = 'receiver_id';

	protected $fillable = ['user_id', 'receive_type'];

	public $timestamps = false;

    public function main_recipients() {
        $receivers = DB::connection('cms')
        ->table('receivers')
        ->where('receive_type', 1)
        ->get();

        $data = array();

        foreach ($receivers as $receiver) {
            // Get user from users table
            $user = DB::table('users')
            ->select('email')
            ->where('user_id', $receiver->user_id)
            ->first();

            $data[] = array(
                'receiver_id' => $receiver->receiver_id,
                'email' => $user->email
            );
        }

        return $data;
    }

    public function carbon_copies() {
        $receivers = DB::connection('cms')
        ->table('receivers')
        ->where('receive_type', 2)
        ->get();

        $data = array();

        foreach ($receivers as $receiver) {
            // Get user from users table
            $user = DB::table('users')
            ->select('email')
            ->where('user_id', $receiver->user_id)
            ->first();

            $data[] = array(
                'receiver_id' => $receiver->receiver_id,
                'email' => $user->email
            );
        }

        return $data;
    }

    public function blind_carbon_copies() {
        $receivers = DB::connection('cms')
        ->table('receivers')
        ->where('receive_type', 3)
        ->get();

        $data = array();

        foreach ($receivers as $receiver) {
            // Get user from users table
            $user = DB::table('users')
            ->select('email')
            ->where('user_id', $receiver->user_id)
            ->first();

            $data[] = array(
                'receiver_id' => $receiver->receiver_id,
                'email' => $user->email
            );
        }

        return $data;
    }

}
