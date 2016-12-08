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
    	$fullname = User::get_fullname();
    	$id = Auth::user()->id;
    	$members = DB::table('members')->where('user_id', $id)->get();

        return view('account.index', compact('members','fullname'));
    }


    public function edit_account()
    {
    	$get_member = User::get_member();
        $fullname = User::get_fullname();
        $get_user = User::get_user();
        foreach($get_user as $user){
            $user_email = $user->email;
        }

        return view('account.edit', compact('get_member','fullname','user_email'));
    }

    public function edit_accountACTION()
    {
        $data = User::edit_account();
        return redirect('account');
    }
}
