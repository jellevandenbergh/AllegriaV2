<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Mail;

use Session;

class Activities extends Model
{
    public $timestamps = false;
    // define protected tables
	protected $table = 'activities';

    // define fillable columns
	protected $fillable=[
		'name',
		'max_members',
        'free_places',
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
        $get_activity_name = DB::table('activities')->where('id', $activity_id)->value('name');
        // return active activites
        return $get_activity_name;
    }

    public static function get_active_activities(){
        // get all active activities
    	$active_activities = DB::table('activities')->where('status', 2)->get();

        // check if max signup hasn't expired
        foreach ($active_activities as $activity) {
            // if max signup has expired add id of activity to array arr[]
            if (strtotime($activity->max_signup_date) < time() - 86400) {
               $arr[] = $activity->id;
            }
            else{
                $arr[] = '';
            }
        }

        // unset activities from $active_activities if activity_id is in $arr[];
        foreach ($active_activities as $key => $obj) {
          if (in_array($obj->id, $arr)) {
            unset($active_activities[$key]);
          }
        } 
        // return active activites
    	return $active_activities;
    }

    public static function get_all_activities(){
        // get all activities
    	$all_activities = DB::table('activities')->get();
        //$hallo = Activities::get_array($all_activities);
        // return all activities
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
        // return signed up activities
        return $signed_up_activities;
    }

    public static function get_array($array){
        /*foreach($array as $price){
            $price_members 
        }*/
    }

    public static function add_activity(){
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
            return false;
		}
		else{
            // get server name
			$server = ($_SERVER["SERVER_NAME"]);
            // create signup url
			$signupUrl =  $server .'/AllegriaV2/public/activities/signup/'. $new_activitie->id;
            // add feedback succes message to session with signupurl
            Session::flash('feedback_success', 'Activiteit toegevoegd! De link voor het aanmelden van deze activiteit is: '.$signupUrl.'');
            return true;
		}

    }

    public static function delete_activity($activity_id){
        // check if activity exist;
        $check_activity = DB::table('activities')->where('id', $activity_id)->get();
        if (!count($check_activity) ==  1) {
            Session::flash('feedback_error', 'Activiteit niet gevonden');
            return flase;
        }


        // delete activity
        DB::table('activities')->where('id', $activity_id)->delete();
        // get all id's from all signups for this activity
        $activities_signup_id = DB::table('activities_signup')->where('activity_id', $activity_id)->pluck('id');
        // delete all intro's where activity_signup_id = $activities_signup_id
        foreach($activities_signup_id as $id){
             DB::table('activities_quest')->where('activity_signup_id', $id)->delete();
        }
        // delete all signups where activity_id = $activity_id
        DB::table('activities_signup')->where('activity_id', $activity_id)->delete();

        // check if signups are deleted
        $check_signup_delete = DB::table('activities_signup')->where('signup_id',$activities_signup_id)->count();
        if(!$check_signup_delete == 0){
            Session::flash('feedback_error', 'Er is iets mis gegaan!');
            return flase;
        }

        // check if activity is deleted
        $check_activity = DB::table('activities')->where('id', $activity_id)->count();
        if (!$check_activity ==  1) {
            Session::flash('feedback_error', 'Er is iets mis gegaan!');
            return flase;
        }
        // add succes message to session
        Session::flash('feedback_success', 'Activiteit verwijderd');
        return true;
    }

    public static function get_activity_by_id($activity_id){
        // get activity by id
	    $get_activity_by_id = DB::table('activities')->where('id', $activity_id)->get();

        // if signup date has expired set status to 1
        foreach($get_activity_by_id as $activity){
            if (strtotime($activity->max_signup_date) < time() - 86400) {
                DB::table('activities')->where('id', $activity->id)->update([
                    'status' => 1,
                ]);
            }
        }

        // return activity to controller
        return $get_activity_by_id;
    }


    public static function check_active_activity($activity_id){
        // check if activity is active
        $get_active_activity = DB::table('activities')
                ->where('id', $activity_id)
                ->where('status', 2)
                ->get();
        if(empty($get_active_activity)){
            return false;
        }
        elseif(count($get_active_activity) == 1){
            return true;
        }
        return false;
    }
    /*public static function formatprice($price){
        $formatprice = '5,00';

        return $formatprice;
    }*/

    public static function overview_ACTION($activity_id)
    {
        // get action
        $action = $_POST['submit'];

        // unset some keys in array
        unset($_POST['submit']);unset($_POST['id']);unset($_POST['_token']);

        // if $_POST is empty show error message
        if (empty($_POST)){
            // add error message to session
            Session::flash('feedback_error', "niemand geselecteerd");
            return false;
        }
        // if $action == "ja" or $action = "nee"
        elseif ($action == "Ja" || $action == "Nee"){
            // set "ja" as 1 and "nee" as 0
            ($action=="Ja")?$action=1:$action=0;
            foreach ($_POST as $POST) {  
                //update signup with $action             
                DB::table('activities_signup')->where('signup_id', $POST)->update([
                    'paid' => $action,
                ]);
            }
            // add success message tot session
            Session::flash('feedback_success', "veranderingen zijn gelukt!");
            return true;
        }

        elseif ($action=="Betaal herinnering"){
            foreach($_POST as $POST){
                // get member id by $POST
                $member_id = DB::table('activities_signup')
                ->where('signup_id', $POST)
                ->value('member_id');

                // get fullname
                $fullname = Members::get_fullname_by_id($member_id);

                // get user id
                $get_user_id = DB::table('members')
                ->where('id', $member_id)
                ->value('user_id');

                // get user email
                $user_email = DB::table('users')
                ->where('id', $get_user_id)
                ->value('email');

                // count amount of intro's signup has
                $get_activitie_signup_intros = DB::table('activities_quest')
                ->where('activity_signup_id', $POST)
                ->count();

                // get price members by activity_id
                $price_members = ActivitiesSignup::get_price_members($activity_id);
                // get price intros by activity_id
                $price_intros = ActivitiesSignup::get_price_intros($activity_id);
                // calculate price
                $amount = $price_intros * $get_activitie_signup_intros + $price_members;
                // get activity name by activity_id
                $activity_name = Activities::get_activity_name($activity_id);

                // send mail
                mail::send('email.payreminder',compact('fullname','amount','activity_name','user_email'), function($message)
                use ($user_email)
                {
                    $message->to($user_email, 'Allegria')->subject('Betaalbevestiging');

                });

            }
            // add succes message to session
            Session::flash('feedback_success', "Het verzenden is gelukt!");
            return true;

        }
        elseif ($action=="Annulering"){
            foreach ($_POST as $POST) {  
                // get free places by activity_id  
                $get_free_places = ActivitiesSignup::get_free_places($activity_id);
                // get max members by activity_id
                $get_max_members = ActivitiesSignup::get_max_members($activity_id);

                // check signup count
                $check_signup = DB::table('activities_signup')
                    ->where('signup_id', $POST)
                    ->count();

                // check is signup is reserve
                $check_if_reserve = DB::table('activities_signup')
                    ->where('signup_id', $POST)
                    ->value('reserve');

                // if checksignup is something else then 1
                if($check_signup != 1){
                    // add error message to session
                    Session::flash('feedback_error', "Er is iets mis gegaan!");
                    return false;
                }
                // if max members is more than free places
                if($get_max_members > $get_free_places){
                    // delete intros
                    $delete_intros = DB::table('activities_quest')
                        ->where('activity_signup_id', $POST)
                        ->delete();

                    // delete the singup
                    $delete_signup = DB::table('activities_signup')
                        ->where('signup_id', $POST)
                        ->delete();

                    // get signup by $POST
                    $check_if_delete = DB::table('activities_signup')
                    ->where('signup_id', $POST)
                    ->count();

                    // check if signup had been deleted
                    if($check_if_delete != 0){
                        Session::flash('feedback_error', "Er is iets mis gegaan!");
                        return false;
                    }

                    // if signup is NOT reserve
                    if($check_if_reserve == 1){
                        // calcutate new free places
                        $free_places = $get_free_places + 1;

                        // update free places
                        DB::table('activities')
                            ->where('id', $activity_id)
                            ->update([
                                'free_places' => $free_places,
                        ]);
                    }

                }
                // if max members is NOT more than free places
                else{
                    // add error message to session
                    Session::flash('feedback_error', "Er is iets mis gegaan!");
                    return false;
                }

                // get activity name by activity_id
                $get_activity_name = Activities::get_activity_name($activity_id);

                // get member id
                $get_member_id = DB::table('activities_signup')
                        ->where('signup_id', $POST)
                        ->value('member_id');

                // get user_id
                $get_user_id = DB::table('members')
                        ->where('id', $get_member_id)
                        ->value('user_id');

                // get user email
                $get_user_email = DB::table('users')
                    ->where('id', $get_user_id)
                    ->value('email');

                // get fullname by member id
                //$get_fullname_by_id =  Members::get_fullname_by_id($get_member_id);

                // send cancel mail
               /* mail::send('email.cancelsignup',compact('get_fullname_by_id','get_activity_name','get_user_email'), function($message)
                use ($get_user_email)
                {
                    $message->to($get_user_email, 'Allegria')->subject('Annulering');

                });*/
            }
            // add success message to session
            Session::flash('feedback_success', "Het annuleren is gelukt!");
            return true;
        }
        elseif ($action=="overzetten naar inschrijvingen"){
            // get free places by activity_id
            $get_free_places = ActivitiesSignup::get_free_places($activity_id);
            // get max members by activity_id
            $get_max_members = ActivitiesSignup::get_max_members($activity_id);

            // if free places is less than the amount of ids wanting to transfer
            if($get_free_places < count($_POST)){
                // if free places is more than 1
                if($get_free_places > 1){
                // add error message to session
                Session::flash('feedback_error', "Niet genoeg plek voor iedereen. Er zijn nog ". $get_free_places . " vrije plekken over");
                }
                // if free places is less than 1
                elseif($get_free_places < 1){
                    // add error message to session
                     Session::flash('feedback_error', "Niet genoeg plek voor iedereen. Er zijn geen vrije plek over");
                }
                else{
                    // add error message to session
                    Session::flash('feedback_error', "Niet genoeg plek voor iedereen. Er is nog ". $get_free_places . " vrije plek over");
                }
                return false;

            }
            foreach($_POST as $POST){
                // if max members is more OR even to free places
                if($get_max_members >= $get_free_places){
                    // check if signup is reserve
                    $check_reserve = DB::table('activities_signup')
                    ->where('signup_id', $POST)
                    ->where('reserve', 2)
                    ->count();

                    // if $check_reserve is something else than 1
                    if($check_reserve != 1){
                        // add error message to session
                        Session::flash('feedback_error', "Er is iets mis gegaan!");
                        return false;
                    }

                    // update signup reserve to 1
                    DB::table('activities_signup')
                    ->where('signup_id', $POST)
                    ->where('reserve', 2)->update([
                        'reserve' => 1,
                    ]);

                    // update intro reserve to 1
                    DB::table('activities_quest')
                    ->where('activity_signup_id', $POST)
                    ->where('reserve', 2)->update([
                        'reserve' => 1,
                    ]);

                    // check if signup reserve = 1
                    $check_reserve = DB::table('activities_signup')
                    ->where('signup_id', $POST)
                    ->where('reserve', 1)
                    ->count();

                    // if $check_reserve is something else than 1
                    if($check_reserve != 1){
                        // add error message to session
                        Session::flash('feedback_error', "Er is iets mis gegaan!");
                        return false;
                    }

                    // get free places by activity_id
                    $get_free_places = ActivitiesSignup::get_free_places($activity_id);
                    // get max members by acivity_id
                    $get_max_members = ActivitiesSignup::get_max_members($activity_id);

                    // if free places is less or even to max members
                    if($get_free_places <= $get_max_members){
                        // calculate new free places
                        $free_places = $get_free_places - 1;
                        // update free places
                        DB::table('activities')
                        ->where('id', $activity_id)
                        ->update([
                            'free_places' => $free_places,
                        ]);
                    }
                }
                else{
                   // add error message to session
                   Session::flash('feedback_error', "Geen plekken meer vrij.");
                   return true; 
                }
            }
            // add success message to session
            Session::flash('feedback_success', "Wijzigingen gelukt");
            return true;
        }
    }
  
}   
