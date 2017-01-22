<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Request;

use App\ActivitiesSignup;
use App\ActivitiesQuest;
use App\Activities;
use App\user;

class ActivitiesController extends Controller
{
    public function index()
    {
        // get active activities
    	$active_activities = Activities::get_active_activities();
        // get all activities
    	$all_activities = Activities::get_all_activities();
        // get activities where user is signed up for
        $signed_up_activities = Activities::get_signed_up_activities();
        // return activities index view
        return view('activities.index', compact('active_activities','all_activities','signed_up_activities'));
    }

    public function add()
    {
        // return activities add view
    	return view('activities.add');
    }
    public function addACTION()
    {
        // call model to handle request
    	Activities::add_activity();
        // redirect to /activities
    	return redirect('activities');
    }

    public function delete($activity_id)
    {
        $activity_name = DB::table('activities')->where('id', $activity_id)->value('name');
        // return activities delete view
        return view('activities.delete', compact('activity_name'));
    }
    public function deleteACTION($activity_id)
    {
        // call model to handle request
        $signupid = Activities::delete_activity($activity_id);
        // redirect to /activities
        return redirect('activities');
    }


    public function overview($activity_id)
    {
        // get activity by id
    	$get_activitie = Activities::get_activity_by_id($activity_id);
        // get members signed up for activitie by activity id
    	//$get_activitie_signup_confirmed = Activities::get_activitie_signup_confirmed($activity_id);
        $get_overview_reserves = ActivitiesSignup::get_overview_reserves($activity_id);

        $get_overview_members = ActivitiesSignup::get_overview_members($activity_id);
        // var_dump($get_overview_reserves);
        //return $get_overview_reserves;

        // return activities overview view
    	return view('activities.overview', compact('get_activitie','get_overview_members','activity_id','get_overview_reserves'));
    }

    public function overviewACTION($activity_id){
        Activities::overview_ACTION($activity_id);
        return redirect('activities/overview/'.$activity_id); 
    }

    public function overviewmembers($activity_id)
    {
        // get activity by id
        $get_overview_members = ActivitiesSignup::get_overview_members($activity_id);
        return json_encode($get_overview_members);
    }

    public function signup($activity_id)
    {
        // get activity by id
    	$get_activitie = Activities::get_activity_by_id($activity_id);
        // get activity name
        $get_activitie_name = Activities::get_activity_name($activity_id);
        // get max intros
        $get_max_intros = ActivitiesSignup::get_max_intros($activity_id);
        // get price members
        $get_price_members = ActivitiesSignup::get_price_members($activity_id);
        // get price intros
        $get_price_intros = ActivitiesSignup::get_price_intros($activity_id);
         // get member
    	$get_member = User::get_member();
        // get fullname of user
        $fullname = User::get_fullname();
        // return activities signup view
    	return view('activities.signup', compact('get_activitie','get_member','activity_id','get_activitie_name','fullname','get_max_intros','get_price_members','get_price_intros'));
    }
    public function signupACTION($activity_id)
    {
        // check if terms and conditions is checked
        if ($_POST['agree'] == "on"){
            // call model to handle request
    	   ActivitiesSignup::signup($activity_id);
        }
        else{
            // if terms and conditions is not checked add error message to session
            Session::flash('feedback_error', 'Accepteer de regelementen');
        }
        // return activities view
        return redirect('activities'); 
    }

    public function signout($activity_id)
    {
        return view('activities.signout');
    }
    public function signoutACTION($activity_id)
    {
        ActivitiesSignup::signout($activity_id);
        return redirect('activities'); 
    }

    public function editintros($activity_id)
    {
        // get quests by activity_id
        $get_intros_by_id = ActivitiesQuest::get_intros_by_id($activity_id);
        // get count of intros
        $intros_count = count($get_intros_by_id);
        // get activity by activity id
        $get_activity_by_id = Activities::get_activity_by_id($activity_id);
        // get activty name by activity id
        $get_activity_name = Activities::get_activity_name($activity_id);
        // get max intros by activity id
        $get_max_intros = ActivitiesSignup::get_max_intros($activity_id);
        // get price intros by activity id
        $get_price_intros = ActivitiesSignup::get_price_intros($activity_id);
        // get price members
        $get_price_members = ActivitiesSignup::get_price_members($activity_id);
        // return activities quest view
        return view('activities.quest', compact('get_intros_by_id','intros_count','get_activity_by_id','get_activity_name','get_max_intros','get_price_intros','get_price_members'));
    }

    public function editintrosACTION($activity_id)
    {
       ActivitiesQuest::addQuest($activity_id);
       return redirect('activities');
    }
    public function confirmsignupACTION($token)
    {
        ActivitiesSignup::confirmsignup($token);
        return redirect('activities');
    }

    public function formatprice($price){
        $formatprice = Activities::formatprice($price);
        return $formatprice;
    }
}

