<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

use Session;

class ActivitiesSignup extends Model
{
    // define protected tables
	protected $table = 'activities_signup';

    // define fillable columns
	protected $fillable=[
		'activity_id',
        'member_id',
        'place',
        'paid',
        'confirmation',
        'datetime_signup',
        'remembersent',
        'comments',
        'price_intros',
        'comments',
        'status',
        'datetime_signout',
    ];


    public static function signupACTION($activity_id)
    {
        // get member id
        $member_id = User::get_member_id();
        // check if user isnt already signup for activity
        $rowcount = ActivitiesSignup::check_dubble_signup($activity_id);
        // check rowcount
        if($rowcount == 1){
            // add error message to session
            Session::flash('feedback_error', 'U bent al aangemeld voor deze activiteit!');
        }
        else{
            // add new signup to database
            $new_activitie_signup = ActivitiesSignup::create([
            'activity_id' => $activity_id,
            'member_id' => $member_id,
            'place' => $_POST['place'],
            'comments' => $_POST['comments'],
        ]);

        // get new signup
        $rowcount = ActivitiesSignup::check_dubble_signup($activity_id);
        // check is user isnt singed up twice
        if($rowcount == 1){
            // add succes message to session
            Session::flash('feedback_success', 'Aanmelden is gelukt!');
        }
        else{
            // if singup is double, delete both signups
            $delete_signup = DB::table('activities_signup')
            ->where('member_id', $member_id)
            ->where('status', 1)
            ->where('activity_id', $activity_id)
            ->delete();
            // add error message to session
            Session::flash('feedback_error', 'Er is iets mis gegaan!, u bent niet aangemeld.');
        }
        }
    }
    public static function check_dubble_signup($activity_id)
    {
        // get member id
        $member_id = User::get_member_id();
        // get signup by member and activity id
        $checkDubbleSignup = DB::table('activities_signup')
            ->where('member_id', $member_id)
            ->where('status', 1)
            ->where('activity_id', $activity_id)
            ->get();

        // count how many signups
        $rowcount = count($checkDubbleSignup);
        // return rowcount
        return $rowcount;
    }
}
