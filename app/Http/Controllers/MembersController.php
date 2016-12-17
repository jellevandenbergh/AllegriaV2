<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Members;

use DB;

use Session;

class MembersController extends Controller
{
    public function index()
    {
        // get all members
    	$members = Members::get_all_members();
        return view('members.index', compact('members'));
    }

    public function add()
    {
        return view('members.add');
    }
    public function addACTION()
    {
    	Members::add_member();
        return redirect('members');
    }

    public function delete($member_id)
    {
        $fullname = Members::get_fullname_by_id($member_id);
        $member = Members::get_member($member_id);
        return view('members.delete', compact('fullname','member'));
    }
    public function deleteACTION($member_id)
    {
        Members::delete_member($member_id);
        return redirect('members');
    }

    public function activatemember($token)
    {
        if(!Members::check_activate_token($token)){
            return view('errors.404');
        }
        $user_creation_timestamp = DB::table('users')->where('user_activation_hash', $token)->value('user_creation_timestamp');
        
        $timestamp = time() - (int)$user_creation_timestamp;

        if ($timestamp > 86400){
            Session::flash('feedback_error', 'Link is verstreken.. vraag een nieuwe link aan');
            return redirect('/login');
            false;
        }
        return view('account.resetpassword');

    }

    public function forgot_password(){
        return view('auth.forgotpassword');
    }

    public function forgot_passwordACTION(){
        Members::forgot_password();
        return redirect('forgotpassword');
    }

    public function reset_password_by_token($token){
        if(!Members::check_reset_password_token($token)){
            return view('errors.404');
        }
        
        $user_password_reset_timestamp = DB::table('users')->where('user_forgot_password_token', $token)->value('user_password_reset_timestamp');
        $timestamp = time() - (int)$user_password_reset_timestamp;

        if ($timestamp > 3600){
            Session::flash('feedback_error', 'Link is verstreken.. vraag een nieuwe link aan');
            return redirect('forgotpassword');
            false;
        }
        return view('account.resetpassword');
    }

    public function sendverification($member_id){
        $fullname = Members::get_fullname_by_id($member_id);
        return view('members.sendverification', compact('fullname'));
        //return redirect('members/edit/' . $member_id .'');
    }

    public function sendverificationACTION($member_id){
        Members::sendverification($member_id);
        return redirect('members/edit/' . $member_id .'');
    }

    public function reset_password_by_tokenACTION($token){
        Members::reset_password_by_token($token);
        return redirect('login');
    }

    public function activatememberACTION($token)
    {
        if(!Members::new_user_set_new_password($token)){
            return view('account.resetpassword');
        }
        return redirect('/login');
    }

    public function edit($member_id)
    {
        // get all members
    	$get_member = Members::get_member($member_id);
    	$fullname = Members::get_fullname_by_id($member_id);

        return view('members.edit', compact('get_member','fullname','member_id'));
    }
    public function editACTION($member_id)
    {
        // get all members
    	Members::edit_member($member_id);
    	// redirect to members
    	return redirect('members');
    }
}
