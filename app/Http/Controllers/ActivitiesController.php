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
        // get activity_name by id
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
        // get signup reserves by activity_id
        $get_overview_reserves = ActivitiesSignup::get_overview_reserves($activity_id);
        // get signups by activity_id
        $get_overview_members = ActivitiesSignup::get_overview_members($activity_id);
        // return view with variables
    	return view('activities.overview', compact('get_activitie','get_overview_members','activity_id','get_overview_reserves','get_free_places'));
    }

    public function overviewACTION($activity_id){
        // call model to handle request
        Activities::overview_ACTION($activity_id);
        // redirect to activity overview page
        return redirect('activities/overview/'.$activity_id);
    }

    public function overviewmembers($activity_id)
    {
        // get activity by id
        $get_overview_members = ActivitiesSignup::get_overview_members($activity_id);
        // return json encoded array
        return json_encode($get_overview_members);
    }

    public function signup($activity_id)
    {
        // get activity by id
    	$get_activitie = Activities::get_activity_by_id($activity_id);
        foreach($get_activitie as $activity){
            $bus = $activity->bus;
        }
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
        $get_free_places = ActivitiesSignup::get_free_places($activity_id);
        // check if user isnt already singedup
        $rowcount = ActivitiesSignup::check_dubble_signup($activity_id);
        // return signup view with variables
    	return view('activities.signup', compact('rowcount','get_free_places','get_activitie','get_member','activity_id','get_activitie_name','fullname','get_max_intros','get_price_members','get_price_intros','bus'));
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
            return false;
        }
        // return activities view
        return redirect('activities');
    }

    public function signout($activity_id)
    {
        // return signout view
        return view('activities.signout');
    }
    public function signoutACTION($activity_id)
    {
        // call model to handle request
        ActivitiesSignup::signout($activity_id);
        // redirect back to activities
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
        return view('activities.quest', compact('get_free_places','get_intros_by_id','intros_count','get_activity_by_id','get_activity_name','get_max_intros','get_price_intros','get_price_members'));
    }

    public function editintrosACTION($activity_id)
    {
        // call model to handle request
       if(!ActivitiesQuest::addQuest($activity_id)){
            return redirect('activities/quest/'.$activity_id.'');
       }
       // redirect back to activities
       return redirect('activities');
    }
    public function confirmsignupACTION($token)
    {
        // call model to handle request
        ActivitiesSignup::confirmsignup($token);
        // redirect back to activities
        return redirect('activities');
    }

    public function passengerlist($activity_id)
    {
        $passengerlist = ActivitiesSignup::getpassengerlist($activity_id);
        
        return view('activities.passengerlist', compact('passengerlist'));
    }

   /* public function formatprice($price){
        // call model to handle request
        $formatprice = Activities::formatprice($price);
        // return formatprice
        return $formatprice;
    }*/
}
