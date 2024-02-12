<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PendingRegistration;
use App\Passwords;
use App\User;
use Hash;
use DB;

class ActivateAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($link) {
        $contacts = $this->contacts();

        // check if link is used already
        $pending_registration = new PendingRegistration;
        $res = $pending_registration->password_link($link);

        if ($res->is_done == 1) {
            return view('activate.expired')->with(compact('contacts'));
        } else {
            return view('activate.index')
            ->with(compact('contacts'))
            ->with(compact('link'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'password' => 'required|same:password_confirmation|min:6',
        ]);

        $password = Hash::make($request->password);

        // Get user id
        $pending_registration = new PendingRegistration;
        $data = $pending_registration->get_user_id($id);

        $user_id = $data->user_id;

        DB::beginTransaction();
        try {
            // $password_data = array(
            //     'password' => $password,
            //     'user_id' => $user_id,
            //     'system_id' => 0
            // );
            // // Add Password
            // Passwords::create($password_data);

            // Update password
            Passwords::where('user_id', $user_id)
            ->update(['password' => $password]);

            // Update is_active status
            $user = User::find($user_id);
            $user->isactive = 1;
            $user->save();

            // Add log
            $log = array(
                'activity_id' => 7,
                'user_id' => $user_id,
                'browser' => $this->browser(),
                'device' => $this->device(),
                'ip_env_address' => $request->ip(),
                'ip_server_address' => request()->server('SERVER_ADDR'),
                'OS' => $this->operating_system()
            );

            $user_model = new User;
            $res = $user_model->add_log($log);

            // Set password link as done
            $password_model = new Passwords();
            $link = $request->link; // link from the email and url
            $res = $password_model->update_password_link($link);

            DB::commit();
            $res = "success";
        } catch (Exception $e) {
            DB::rollback();
            $res = $e->getMessage();
        }

        if ($res == "success") {
            $contacts = $this->contacts();
            return view('activate.success')->with(compact('contacts'));
        } else {
            echo $res;
        }
    }
}
