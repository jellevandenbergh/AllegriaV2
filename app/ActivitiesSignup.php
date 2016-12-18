<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Auth;
use Session;
use Mail;

class ActivitiesSignup extends Model
{
    public $timestamps = false;
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
        'confirmation_token',
        'comments',
        'price_intros',
        'comments',
        'status',
        'datetime_signout',
    ];


    public static function signup($activity_id)
    {
        // get member id
        $member_id = User::get_member_id();
        // check if user isnt already signup for activity
        $rowcount = ActivitiesSignup::check_dubble_signup($activity_id);

        $get_max_intros = ActivitiesSignup::get_max_intros($activity_id);
        // check rowcount
        if($rowcount == 1){
            // add error message to session
            Session::flash('feedback_error', 'U bent al aangemeld voor deze activiteit!');
        }
        else{
            $token = hash_hmac('sha256', str_random(40), config('app.key'));
            // add new signup to database
            $new_activitie_signup = ActivitiesSignup::create([
            'activity_id' => $activity_id,
            'member_id' => $member_id,
            'place' => $_POST['place'],
            'comments' => $_POST['comments'],
            'confirmation_token' => $token,
            ]);

            // add new signupQuest to database
            for ($i=1; $i <= $_POST['max_intros']; $i++) { 
                $new_activitie_signupQuest = ActivitiesQuest::create([
                    'activity_signup_id' => $new_activitie_signup->id,
                    'name' => $_POST['name-intro-'.$i],
                    'birthday' => $_POST['birthday-intro-'.$i],
                    'comments' => $_POST['comments-intro-'.$i],
                ]);
            }

            // get new signup
            $rowcount = ActivitiesSignup::check_dubble_signup($activity_id);
            // check is user isnt singed up twice
            if($rowcount == 1){
                // add succes message to session
                $fullname = User::get_fullname();
                $get_activitie_name = Activities::get_activity_name($activity_id);
                $server = ($_SERVER["SERVER_NAME"]);
                // create confirm url
                $activationlink =  $server .'/AllegriaV2/public/activities/confirmsignup/'.$token;
                mail::send('email.confirmsignup',compact('fullname','activationlink','get_activitie_name'), function($message)
                {
                    $message->to(Auth::user()->email, 'Allegria')->subject('Aanmelding bevestigen');
                });
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

    public static function confirmsignup($token)
    {
        $check_token = DB::table('activities_signup')->where('confirmation_token', $token)->get();
        if (!count($check_token) ==  1) {
            Session::flash('feedback_error', 'Aanmelding niet gevonden');
            return false;
        }

        DB::table('activities_signup')->where('confirmation_token', $token)->update([
            'confirmation_token' => NULL,
            'confirmation' => '2',
        ]);
        Session::flash('feedback_success', 'Aanmelding bevestigt!');
        return true;
    }

    public static function get_max_intros($activity_id)
    {
        $get_max_intros = DB::table('activities')->where('id', $activity_id)->value('max_intros');

        return $get_max_intros;
    }

    public static function get_price_intros($activity_id)
    {
        $get_price_intros = DB::table('activities')->where('id', $activity_id)->value('price_intros');

        return $get_price_intros;
    }

    public static function get_price_members($activity_id)
    {
        $get_price_members = DB::table('activities')->where('id', $activity_id)->value('price_members');

        return $get_price_members;
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
