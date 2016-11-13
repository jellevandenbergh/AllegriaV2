<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Request;

use App\ActivitiesSignup;
use App\Activities;
use App\user;

class ActivitiesController extends Controller
{
    public function index()
    {
    	$user_id = Auth::user()->id;
    	$active_activities = Activities::get_active_activities();
    	$all_activities = Activities::get_all_activities();
        $signed_up_activities = Activities::get_signed_up_activities();

        return view('activities.index', compact('active_activities','all_activities','signed_up_activities'));
    }

    public function add()
    {
    	return view('activities.add');
    }

    public function addACTION()
    {
    	$feedback = Activities::addAction();
    	return redirect('activities');
    }


    public function overview($activity_id)
    {
    	$get_activitie = Activities::get_activitie_by_id($activity_id);
    	$get_activitie_signup = Activities::get_activitie_signup($activity_id);
    	return view('activities.overview', compact('get_activitie','get_activitie_signup'));
    }


    public function signup($activity_id)
    {
    	$get_activitie = Activities::get_activitie_by_id($activity_id);
    	$get_member = User::get_member();
    	return view('activities.signup', compact('get_activitie','get_member','activity_id'));
    }

    public function signupACTION($activity_id)
    {
    	$new_signup = ActivitiesSignup::signupACTION($activity_id);
    	return redirect('activities');
    }
}

