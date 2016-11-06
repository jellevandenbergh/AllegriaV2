<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;

use App\Activities;
use App\user;

class ActivitiesController extends Controller
{
    public function index()
    {
    	$id = Auth::user()->id;
    	$active_activities = Activities::get_active_activities($id);
    	$all_activities = Activities::get_all_activities($id);
        $signed_up_activities = Activities::get_signed_up_activities($id);

        return view('activities.index', compact('active_activities','all_activities','signed_up_activities'));
    }

    public function add()
    {
    	return view('activities.add');
    }

    public function addACTION()
    {
    	Activities::addAction();
    	return redirect('activities'); 
    }


    public function overview($id)
    {
    	$get_activitie = Activities::get_activitie_by_id($id);
    	$get_activitie_signup = Activities::get_activitie_signup($id);
    	return view('activities.overview', compact('get_activitie','get_activitie_signup'));
    }

    public function signup($id)
    {
    	$id = Auth::user()->id;
    	$get_activitie = Activities::get_activitie_by_id($id);
    	$get_member = User::get_member($id);
    	return view('activities.signup', compact('get_activitie','get_member'));
    }

    public function signupACTION($id)
    {
    	
    }
}

