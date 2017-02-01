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
        'reserve',
        'datetime_signout',
    ];


    public static function signup($activity_id)
    {
        // get member id
        $member_id = User::get_member_id();
        // check if user isnt already signup for activity
        $rowcount = ActivitiesSignup::check_dubble_signup($activity_id);
        // get max intros by activity_id
        $get_max_intros = ActivitiesSignup::get_max_intros($activity_id);
        // check rowcount
        if($rowcount >= 1){
            // add error message to session
            Session::flash('feedback_error', 'U bent al aangemeld voor deze activiteit!');
            return false;
        }
        // if model return false
        if(!Activities::check_active_activity($activity_id)){
            Session::flash('feedback_error', 'Activiteit is niet actief!');
            return false;
        }

        // get max members
        $get_max_members = ActivitiesSignup::get_max_members($activity_id);
        // get free places
        $get_free_places = ActivitiesSignup::get_free_places($activity_id);
        // get max reserves
        $get_max_reserves = ActivitiesSignup::get_max_reserves($activity_id);
        // get reserves count
        $get_reserves_count = ActivitiesSignup::get_reserves_count($activity_id);

        /*if($get_free_places <= 0){
            Session::flash('feedback_error', 'Er zijn geen vrije plekken meer beschikbaar');
            return false;
        }*/
        // get activity by id
        $activity_by_id = Activities::get_activity_by_id($activity_id);

        foreach($activity_by_id as $activity){
            if($activity->bus == 1){
                $_POST['place'] = NULL;
            }
        }

        //check is signup date has expired
        foreach ($activity_by_id as $activity) {
            if (strtotime($activity->max_signup_date) < time() - 86400) {
               Session::flash('feedback_error', 'uiterste inschrijfdatum verlopen! u bent niet aangemeld');
               return false;
            }
        }

        // create new token
        $token = hash_hmac('sha256', str_random(40), config('app.key'));

        // add new signup to database
        if($get_free_places < 1){
            // if get_max reserves is more or even to reserves count and max reserves is more than 0
            if($get_max_reserves >= $get_reserves_count AND $get_max_reserves > 0){
                $new_activitie_signup = ActivitiesSignup::create([
                    'activity_id' => $activity_id,
                    'member_id' => $member_id,
                    'place' => $_POST['place'],
                    'comments' => $_POST['comments'],
                    'confirmation_token' => NULL,
                    'status' => 2,
                    'confirmation' => 2,
                    'reserve' => 2,
                ]);
            }
            else{
                // add error message to session
                Session::flash('feedback_error', 'Er waren geen vrije plekken meer beschikbaar en de reservelijst is ook vol. U bent niet aangemeld voor deze activiteit');
                return false;
            }

            // add new signupQuest to database
            for ($i=1; $i <= $_POST['max_intros']; $i++) {
                $new_activitie_signupQuest = ActivitiesQuest::create([
                    'activity_signup_id' => $new_activitie_signup->id,
                    'name' => $_POST['name-intro-'.$i],
                    'birthday' => $_POST['birthday-intro-'.$i],
                    'comments' => $_POST['comments-intro-'.$i],
                    'reserve' => 2,
                ]);
            }
            // add success message to session
            Session::flash('feedback_success', 'Er waren geen vrije plekken meer beschikbaar. U bent aangemeld voor de reservelijst');
        }
        else{
            // create new activity signup
            $new_activitie_signup = ActivitiesSignup::create([
                'activity_id' => $activity_id,
                'member_id' => $member_id,
                'place' => $_POST['place'],
                'comments' => $_POST['comments'],
                'confirmation_token' => $token,
                'status' => 2,
                'confirmation' => 2,
                'reserve' => 1,
            ]);

            // add new signupQuest to database
            for ($i=1; $i <= $_POST['max_intros']; $i++) {
                $new_activitie_signupQuest = ActivitiesQuest::create([
                    'activity_signup_id' => $new_activitie_signup->id,
                    'name' => $_POST['name-intro-'.$i],
                    'birthday' => $_POST['birthday-intro-'.$i],
                    'comments' => $_POST['comments-intro-'.$i],
                    'reserve' => 1,
                ]);
            }
            // add succes message to session
            Session::flash('feedback_success', 'Aanmelden is gelukt!');
        }

        //if free places is more or even to 1
        if($get_free_places >= 1){
            // calculate new free places
            $free_places = $get_free_places - 1;
            // update free places
            DB::table('activities')->where('id', $activity_id)->update([
                'free_places' => $free_places,
            ]);
        }
            // get new signup
            $rowcount = ActivitiesSignup::check_dubble_signup($activity_id);

            // check is user isnt singed up twice
            if($rowcount > 1){
                // if singup is double, delete both signups
                $get_signup_id = DB::table('activities_signup')
                 ->where('member_id', $member_id)
                ->where('activity_id', $activity_id)
                ->pluck('id');

                $delete_signup = DB::table('activities_signup')
                ->where('member_id', $member_id)
                ->where('activity_id', $activity_id)
                ->delete();

                // delete intros
                $delete_intros = DB::table('activities_quest')
                ->whereIn('activity_signup_id', $get_signup_id)
                ->delete();

                // get free places
                $get_free_places = ActivitiesSignup::get_free_places($activity_id);
                // calculate new free places
                $free_places = $get_free_places + $rowcount;
                // update fre places
                DB::table('activities')->where('id', $activity_id)->update([
                    'free_places' => $free_places,
                ]);

                // add error message to session
                Session::flash('feedback_error', 'Er is iets mis gegaan!, u bent niet aangemeld.');
                return false;
            }
        return true;
    }

    public static function signout($activity_id){
        // get member id
        $member_id = User::get_member_id();

        // get max members
        $get_max_members = ActivitiesSignup::get_max_members($activity_id);

        // get free places
        $get_free_places = ActivitiesSignup::get_free_places($activity_id);

        // calculate new free places
        $free_places = $get_free_places + 1;

        // if free places is more then max members
        if($free_places > $get_max_members){
            Session::flash('feedback_error', 'Er is iets mis gegaan!, u bent niet afgemeld.');
            return false;
        }

        // get activity signup id
        $activity_signup_id = DB::table('activities_signup')
            ->where('member_id', $member_id)
            ->where('activity_id', $activity_id)
            ->value('signup_id');

        // get reserve from signup
        $activity_reserve = DB::table('activities_signup')
            ->where('member_id', $member_id)
            ->where('activity_id', $activity_id)
            ->value('reserve');

        // delete signup
        DB::table('activities_signup')
            ->where('member_id', $member_id)
            ->where('activity_id', $activity_id)
            ->delete();

        // delete intros
        DB::table('activities_quest')
            ->where('activity_signup_id', $activity_signup_id)
            ->delete();

        // check is signup is deleted
        $check_signup_delete = DB::table('activities_signup')
            ->where('signup_id', $activity_signup_id)
            ->count();

        // check is intros are deleted
        $check_quest_delete = DB::table('activities_quest')
            ->where('activity_signup_id', $activity_signup_id)
            ->count();

        // if $check_quest_delete is something else then 0
        if($check_quest_delete != 0){
            return false;
            // add error message to session
            Session::flash('feedback_error', 'Er is iets mis gegaan!, u bent niet afgemeld.');
        }

        if($check_signup_delete != 0){
            return false;
            // add error message to session
            Session::flash('feedback_error', 'Er is iets mis gegaan!, u bent niet afgemeld.');
        }

        // if singup reserve = 1
        if($activity_reserve == 1){
            // update free places
            DB::table('activities')->where('id', $activity_id)->update([
                'free_places' => $free_places,
            ]);
        }
        else{
            // add succes message to session
            Session::flash('feedback_success', 'U bent afgemeld van de reservelijst');
            return true;
        }
        // add succes message to session
        Session::flash('feedback_success', 'U bent afgemeld');
        return true;
    }
    /*public static function confirmsignup($token)
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
    }*/

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

    public static function get_max_members($activity_id)
    {
        $get_max_members = DB::table('activities')->where('id', $activity_id)->value('max_members');

        return $get_max_members;
    }

    public static function get_free_places($activity_id)
    {
        $get_free_places = DB::table('activities')->where('id', $activity_id)->value('free_places');

        return $get_free_places;
    }

    public static function get_max_reserves($activity_id)
    {
        $get_max_reserves = DB::table('activities')->where('id', $activity_id)->value('max_reserves');

        return $get_max_reserves;
    }

    public static function get_reserves_count($activity_id)
    {
        // check reseres count
        $get_reserves_count = DB::table('activities_signup')
        ->where('activity_id', $activity_id)
        ->where('reserve', 2)
        ->count();

        // return reserves count
        return $get_reserves_count;
    }

    public static function get_reserves_signup($activity_id)
    {
        // get reserves with singup id
        $get_reserves_count = DB::table('activities_signup')
        ->where('activity_id', $activity_id)
        ->where('reserve', 2)
        ->get();

        // return reserves count
        return $get_reserves_count;
    }

    public static function check_dubble_signup($activity_id)
    {
        // get member id
        $member_id = User::get_member_id();
        // get signup by member and activity id
        $checkDubbleSignup = DB::table('activities_signup')
            ->where('member_id', $member_id)
            ->where('activity_id', $activity_id)
            ->get();

        // count how many signups
        $rowcount = count($checkDubbleSignup);
        // return rowcount
        return $rowcount;
    }

    public static function get_overview_members($activity_id){
        // get activity signup
        $get_activitie_signup = DB::table('activities_signup')
                ->where('activity_id', $activity_id)
                ->where('confirmation', 2)
                ->where('reserve', 1)
                ->join('members', 'member_id', '=', 'members.id')
                ->join('users', 'members.user_id', '=', 'users.id')
                ->get();

        // get singups with activity_id
        foreach($get_activitie_signup as $activity){
            // get intros count with signup id
            $get_activitie_signup_intros = DB::table('activities_quest')
            ->where('activity_signup_id', $activity->signup_id)
            ->count();

            // get price members
            $price_members = ActivitiesSignup::get_price_members($activity_id);
            // get price intros
            $price_intros = ActivitiesSignup::get_price_intros($activity_id);
            // calculate total price
            $amount = $price_intros * $get_activitie_signup_intros + $price_members;

            // add amount to stdclass
            $activity->amount = $amount;
            // add intros count to stdclass
            $activity->singup_intros = $get_activitie_signup_intros;

        }


        // return activity signup
        return $get_activitie_signup;
    }

    public static function get_overview_reserves($activity_id){
        // get activity signup reserve
        $get_activitie_reserves = DB::table('activities_signup')
                ->where('activity_id', $activity_id)
                ->where('confirmation', 2)
                ->where('reserve', 2)
                ->join('members', 'member_id', '=', 'members.id')
                ->join('users', 'members.user_id', '=', 'users.id')
                ->get();


        foreach($get_activitie_reserves as $activity){
             // get intros count with signup id
            $get_activitie_signup_intros = DB::table('activities_quest')
            ->where('activity_signup_id', $activity->signup_id)
            ->count();

            // get price members
            $price_members = ActivitiesSignup::get_price_members($activity_id);
            // get price intros
            $price_intros = ActivitiesSignup::get_price_intros($activity_id);
            // calculate total price
            $amount = $price_intros * $get_activitie_signup_intros + $price_members;

            // is paid = 0
            if($activity->paid == 0){
                // set paid to "nee"
                $activity->paid = "Nee";
            }
            else{
                // set paid to "ja"
                $activity->paid = "ja";
            }

            // add amount to stdclass
            $activity->amount = $amount;
            // add intros count to stdclass
            $activity->singup_intros = $get_activitie_signup_intros;

        }
        // return activity signup reserve
        return $get_activitie_reserves;
    }



    public static function getpassengerlist($activity_id){
        $list['lid'] = DB::table('activities_signup')
                ->leftjoin('members', 'activities_signup.member_id', '=', 'members.id')
                ->leftjoin('users', 'members.user_id', '=', 'users.id')
                ->where('activities_signup.reserve', 1)
                ->select('members.lastname','members.insertion','members.firstname','members.birthday','activities_signup.paid','users.email','activities_signup.datetime_signup')
                ->get();

        foreach($list['lid'] as $passenger){
            if($passenger->paid <= 0){
                $passenger->paid = "Nee";
            }
            else{
                $passenger->paid = "ja";
            }
        }
        
        $signup_ids = DB::table('activities_signup')
                ->where('activity_id', $activity_id)
                ->pluck('signup_id');

        $list['intros'] = DB::table('activities_quest')
                ->whereIn('activity_signup_id', $signup_ids)
                ->select('name','birthday')
                ->get();

        return $list;
    }
}
