<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Auth;
use Session;
use Mail;

class ActivitiesQuest extends Model
{
    public $timestamps = false;
    // define protected tables
	protected $table = 'activities_quest';

    // define fillable columns
	protected $fillable=[
		'activity_signup_id',
        'name',
        'birthday',
        'comments',
        'reserve',
    ];

    public static function addQuest($activity_id){
     /*if (!$POST) {
            Session::flash('feedback_error', 'Er is iets misgegaan!');
            return false;
        } elseif (!ActivitiesModel::checkActiveActivity($activity_id) {
            Session::add('feedback_negative', Text::get('FEEDBACK_ACTIVITIES_SIGNUPDATE_FAILED'));
            return false;
        } elseif (!ActivitiesModel::checkDubbleSignup($POST['activity_id'])) {
            Session::add('feedback_negative', Text::get('FEEDBACK_ACTIVITIES_DUBBLESIGNUP_FAILED'));
            return false;
        }*/
        // get intros by id
        $get_intros_by_id = ActivitiesQuest::get_intros_by_id($activity_id);
        // get count of intros
        $intros_count = count($get_intros_by_id);
        // get member id
        $get_member_id = User::get_member_id();
        // get activity singup id
        $activity_signup_id = DB::table('activities_signup')
                ->where('activity_id', $activity_id)
                ->where('member_id', $get_member_id)
                ->value('signup_id');

        // get max intros by activity_id
        $get_max_intros = ActivitiesSignup::get_max_intros($activity_id);
        // run for loop to put new intros in database
        for ($i=$intros_count + 1; $i <= $get_max_intros; $i++) { 
        	// if $POST name intro  is somthing else than ""
            if (!$_POST['name-intro-'.$i.''] == ""){
            	// if $POST birthday-intro is somthing else than ""
            	if(!$_POST['birthday-intro-'.$i.''] == ""){
	                $new_signupQuest = ActivitiesQuest::create([
	                    'activity_signup_id' => $activity_signup_id,
	                    'name' => $_POST['name-intro-'.$i],
	                    'birthday' => $_POST['birthday-intro-'.$i],
	                    'comments' => $_POST['comments-intro-'.$i],
	                ]);
	                
	                if (!count($new_signupQuest) == 1) {
	                    Session::flash('feedback_error', 'Aanmelding mislukt!');
	                return false;
	                }
            	}
            	// if birthday intro is not filled in and name intro is filled in
            	elseif($_POST['birthday-intro-'.$i.''] == "" AND !$_POST['name-intro-'.$i.''] == ""){
            		// add feedback error to session
	            	Session::flash('feedback_error', 'Geen geboortedatum ingevoerd');
	            	return false;
            	}
            }
            // if birthday intro is filled in and name intro is not filled in
            elseif(!$_POST['birthday-intro-'.$i.''] == "" AND $_POST['name-intro-'.$i.''] == ""){
            	// add feedback error to session
            	Session::flash('feedback_error', 'Geen naam ingevoerd');
            	return false;
            }
        }
        // add success message to session
        Session::flash('feedback_success', 'Introduces aangemeld!');
        return true;
    }

    public static function get_intros_by_id($activity_id){
    	// get member
    	$member_id = User::get_member_id();
    	// check signup ad get signup id
    	$check_signup = DB::table('activities_signup')
    		->where('activity_id', $activity_id)
    		->where('member_id', $member_id)
    		->pluck('signup_id');
    	// if $check_signup count is something else than 1
        if (!count($check_signup) ==  1) {
            //Session::flash('feedback_error', 'Geen aanmeldingen gevonden');
            return false;
        }
        // get intro''s by signup id
        $get_intros_by_id = DB::table('activities_quest')->where('activity_signup_id', $check_signup)->get();
        // return intros
        return $get_intros_by_id;
    }
}
