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
                ->value('id');

        // get max intros
        $get_max_intros = ActivitiesSignup::get_max_intros($activity_id);
        for ($i=$intros_count + 1; $i <= $get_max_intros; $i++) { 
            if (!$_POST['name-intro-'.$i.''] == ""){
                
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
        }
        Session::flash('feedback_success', 'Introduces aangemeld!');
        return true;
    }

    public static function get_intros_by_id($activity_id){
    	$member_id = User::get_member_id();
    	$check_signup = DB::table('activities_signup')
    		->where('activity_id', $activity_id)
    		->where('member_id', $member_id)
    		->pluck('id');
        if (!count($check_signup) ==  1) {
            //Session::flash('feedback_error', 'Geen aanmeldingen gevonden');
            return false;
        }
        $get_intros_by_id = DB::table('activities_quest')->where('activity_signup_id', $check_signup)->get();

        return $get_intros_by_id;
    }
}
