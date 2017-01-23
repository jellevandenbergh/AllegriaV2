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
        // return members index view
        return view('members.index', compact('members'));
    }

    public function getmembers(){
        // get all members
        $members = Members::get_all_members();
        // return json encoded array
        return json_encode($members);
    }
    public function add()
    {
        // return members add view
        return view('members.add');
    }
    public function addACTION()
    {
        // call model to handle request
    	Members::add_member();
        // redirect back to members
        return redirect('members');
    }

    public function delete($member_id)
    {
        // get fullname by member_id
        $fullname = Members::get_fullname_by_id($member_id);
        // get member by id
        $member = Members::get_member($member_id);
        // return delete view with variables
        return view('members.delete', compact('fullname','member'));
    }
    public function deleteACTION($member_id)
    {
        // call model to handle request
        Members::delete_member($member_id);
        // redirect to members
        return redirect('members');
    }

    public function activatemember($token)
    {
        // call model to handle request
        // if token is not found return error 404 page
        if(!Members::check_activate_token($token)){
            return view('errors.404');
        }
        // check user_creation_timestamp
        $user_creation_timestamp = DB::table('users')->where('user_activation_hash', $token)->value('user_creation_timestamp');
        
        // calculate time passed on user_creation_timestamp
        $timestamp = time() - (int)$user_creation_timestamp;

        // if there is more than 24 hours passed (86400 sec) redirect to login
        if ($timestamp > 86400){
            // add error message to session
            Session::flash('feedback_error', 'Link is verstreken.. vraag een nieuwe link aan');
            return redirect('/login');
        }
        // return view resetpassowrd
        return view('account.resetpassword');
    }

    public function forgot_password(){
        // return view forgotpassword
        return view('auth.forgotpassword');
    }

    public function forgot_passwordACTION(){
        // call model to handle request
        Members::forgot_password();
        // redirect to forgotpassword
        return redirect('forgotpassword');
    }

    public function reset_password_by_token($token){
        // call model to handle request
        // if token is not found return error 404 page
        if(!Members::check_reset_password_token($token)){
            return view('errors.404');
        }
        
         // check user_password_reset_timestamp
        $user_password_reset_timestamp = DB::table('users')->where('user_forgot_password_token', $token)->value('user_password_reset_timestamp');

        // calculate time passed on user_password_reset_timestamp
        $timestamp = time() - (int)$user_password_reset_timestamp;

        // if timestamp is more than 1 hour(3600 sec) redirect back to forgotpassword
        if ($timestamp > 3600){
            // add error message to session
            Session::flash('feedback_error', 'Link is verstreken.. vraag een nieuwe link aan');
            return redirect('forgotpassword');
            false;
        }
        // return view resetpassword
        return view('account.resetpassword');
    }

    public function sendverification($member_id){
        // get fullname by member_id
        $fullname = Members::get_fullname_by_id($member_id);
        // return sendverification view with variable
        return view('members.sendverification', compact('fullname'));
    }

    public function sendverificationACTION($member_id){
        // call model to handle request
        Members::sendverification($member_id);
        // redirect back to members edit
        return redirect('members/edit/' . $member_id .'');
    }

    public function reset_password_by_tokenACTION($token){
        // call model to handle request
        Members::reset_password_by_token($token);
        // redirect to login
        return redirect('login');
    }

    public function activatememberACTION($token)
    {
        // call model to handle request
        // if model returns true
        if(!Members::new_user_set_new_password($token)){
            // return resetpasword view
            return view('account.resetpassword');
        }
        // redirect to login
        return redirect('login');
    }

    public function edit($member_id)
    {
        // get member
    	$get_member = Members::get_member($member_id);
        // get fullname by member_id
    	$fullname = Members::get_fullname_by_id($member_id);
        // return members edit view with variables
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
