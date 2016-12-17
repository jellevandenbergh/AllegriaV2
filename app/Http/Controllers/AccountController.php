<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use DB;
use Auth;

class AccountController extends Controller
{
    public function index()
    {
        // get fullname
    	$fullname = User::get_fullname();
        // get member
    	$get_member = User::get_member();
        // return account index view
        return view('account.index', compact('get_member','fullname'));
    }

    public function edit_account()
    {
        // get member
    	$get_member = User::get_member();
        // get fullname
        $fullname = User::get_fullname();
        // get user id
        $id = Auth::user()->id;
        // get user email
        $user_email = DB::table('users')->where('id', $id)
        ->value('email');
        // return account edit view
        return view('account.edit', compact('get_member','fullname','user_email'));
    }
    public function edit_accountACTION()
    {
        // call model to handle request
        User::edit_account();
        // return account view
        return redirect('account');
    }

    public function editpassword()
    {
        return view('account.resetpassword');
    }
    public function editpasswordACTION()
    {
       User::edit_password();
       return redirect('account');
    }
}
