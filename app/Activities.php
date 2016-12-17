<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

use Session;

class Activities extends Model
{
    // define protected tables
	protected $table = 'activities';

    // define fillable columns
	protected $fillable=[
		'name',
		'max_members',
        'status',
        'date',
        'max_intros',
        'bus_boarding_point',
        'bus_amount',
        'max_signup_date',
        'price_members',
        'price_intros',
        'comments',
        'max_reserves',
    ];

    public static function get_activity_name($activity_id){
        // get all active activities
        $get_activitie_name = DB::table('activities')->where('id', $activity_id)->value('name');
        // return active activites to controller
        return $get_activitie_name;
    }

    public static function get_active_activities(){
        // get all active activities
    	$active_activities = DB::table('activities')->where('status', 2)->get();
        // return active activites to controller
    	return $active_activities;
    }

    public static function get_all_activities(){
        // get all activities
    	$all_activities = DB::table('activities')->get();
        // return all activities to controller
    	return $all_activities;
    }

    public static function get_signed_up_activities(){
        // get member id by user id
    	$member_id = User::get_member_id();
        // get activities where user is signed up for
	    $signed_up_activities = DB::table('activities_signup')
	            ->join('activities', 'activity_id', '=', 'activities.id')
	       		->where('member_id', $member_id)
	            ->get();
        // return signed up activities to controller
        return $signed_up_activities;
    }

    public static function addACTION(){
        // put . in price members
    	$_POST['price_members'] = str_replace(array(',', '.'), '',$_POST['price_members']);
        // put . in price intro's
        $_POST['price_intros'] = str_replace(array(',', '.'), '',$_POST['price_intros']);

        // check if max intro's = 0 if so, price intro' is also 0
        if ($_POST['max_intros'] == 0) {
            $_POST['price_intros'] = 0;
        }

        // check if bus = 1 if so, bus boarding point and bus amount is also 0
        if( $_POST['bus'] == '1'){
        	$_POST['bus_boarding_point'] = '';
        	$_POST['bus_amount'] = 0;
        }

        // Add new activitie to database
		$new_activitie = Activities::create([
			'name' => $_POST['name'],
			'max_members' => $_POST['max_members'],
			'status' => $_POST['status'],
			'date' => $_POST['date'],
			'max_intros' => $_POST['max_intros'],
			'bus_boarding_point' => $_POST['bus_boarding_point'],
			'bus_amount' => $_POST['bus_amount'],
			'free_places' => $_POST['max_members'],
			'free_reserves' => $_POST['max_reserves'],
			'max_signup_date' => $_POST['max_signup_date'],
			'price_members' => $_POST['price_members'],
			'price_intros' => $_POST['price_intros'],
			'comments' => $_POST['comments'],
			'max_reserves' => $_POST['max_reserves'],
		]);

        // get new activity by id
		$get_new_activitie = DB::table('activities')->where('id', $new_activitie->id)->get();

        // check if new activity exist
		if ($get_new_activitie == "[]") {
            // if new activity is not found add error message to session
		    Session::flash('feedback_error', 'Er is iets mis gegaan!');
		}
		else{
            // get server name
			$server = ($_SERVER["SERVER_NAME"]);
            // create signup url
			$signupUrl =  $server .'/AllegriaV2/public/activities/signup/'. $new_activitie->id;
            // add feedback succes message to session with signupurl
			Session::flash('feedback_success', 'Activiteit toegevoegd! De link voor het aanmelden van deze activiteit is: '.$signupUrl.'');
		}

    }

    public static function deleteACTION($activity_id){
        DB::table('activities')->where('id', $activity_id)->delete();
        DB::table('activities_signup')->where('activity_id', $activity_id)->delete();
    }

    public static function get_activitie_by_id($activity_id){
        // get activity by id
	    $get_activitie = DB::table('activities')->where('id', $activity_id)->get();

        // return activity to controller
        return $get_activitie;
    }

    public static function get_activitie_signup($activity_id){
        // get activity signup
        $get_activitie_signup = DB::table('activities_signup')
                ->join('activities', 'activity_id', '=', 'activities.id')
                ->join('members', 'member_id', '=', 'members.id')
                ->join('users', 'members.user_id', '=', 'users.id')
                ->where('activities.id',$activity_id)
                ->get();
        // return activity signup to controller  
    	return $get_activitie_signup;
    }
  
}
