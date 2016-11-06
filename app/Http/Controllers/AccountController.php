<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;

class AccountController extends Controller
{
    public function index()
    {
    	$id = Auth::user()->id;
    	$members = DB::table('members')->where('user_id', $id)->get();

        return view('account.index', compact('members'));
    }
}
