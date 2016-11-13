<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

use Session;

class ActivitiesSignup extends Model
{
	protected $table = 'activities_signup';

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
        $member_id = User::get_member_id();
        $rowcount = ActivitiesSignup::check_dubble_signup($activity_id);
        if($rowcount == 1){
            Session::flash('feedback_error', 'U bent al aangemeld voor deze activiteit!');
        }
        else{
            $new_activitie_signup = ActivitiesSignup::create([
            'activity_id' => $activity_id,
            'member_id' => $member_id,
            'place' => $_POST['place'],
            'comments' => $_POST['comments'],
        ]);
        $rowcount = ActivitiesSignup::check_dubble_signup($activity_id);
        if($rowcount == 1){
            Session::flash('feedback_success', 'Aanmelden is gelukt!');
        }
        else{
            $delete_signup = DB::table('activities_signup')
            ->where('member_id', $member_id)
            ->where('status', 1)
            ->where('activity_id', $activity_id)
            ->delete();
            Session::flash('feedback_error', 'Er is iets mis gegaan!, u bent niet aangemeld.');
        }
        }
    }


























    public static function check_dubble_signup($activity_id)
    {
        $member_id = User::get_member_id();
        $checkDubbleSignup = DB::table('activities_signup')
            ->where('member_id', $member_id)
            ->where('status', 1)
            ->where('activity_id', $activity_id)
            ->get();

        $rowcount = count($checkDubbleSignup);
        return $rowcount;
    }
}
