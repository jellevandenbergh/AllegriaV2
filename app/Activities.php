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
        // return active activites to controller
        return $get_activity_name;
    }

    public static function get_active_activities(){
        // get all active activities
    	$active_activities = DB::table('activities')->where('status', 2)->get();


        foreach ($active_activities as $activity) {
            if (strtotime($activity->max_signup_date) < time() - 86400) {
               $arr[] = $activity->id;
            }
            else{
                $arr[] = '';
            }
        }

        foreach ($active_activities as $key => $obj) {
          if (in_array($obj->id, $arr)) {
            unset($active_activities[$key]);
          }
        } 
        // return active activites to controller
    	return $active_activities;
    }

    public static function get_all_activities(){
        // get all activities
    	$all_activities = DB::table('activities')->get();

        $hallo = Activities::get_array($all_activities);

        return $hallo;
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

    public static function get_array($array){
        return $array;
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

    public static function delete_activity($activity_id){
        $check_activity = DB::table('activities')->where('id', $activity_id)->get();
        if (!count($check_activity) ==  1) {
            Session::flash('feedback_error', 'Activiteit niet gevonden');
            return flase;
        }

        DB::table('activities')->where('id', $activity_id)->delete();
        $activities_signup_id = DB::table('activities_signup')->where('activity_id', $activity_id)->pluck('id');
        foreach($activities_signup_id as $id){
             DB::table('activities_quest')->where('activity_signup_id', $id)->delete();
        }
        DB::table('activities_signup')->where('activity_id', $activity_id)->delete();
        Session::flash('feedback_success', 'Activiteit verwijderd');
        return true;
    }

    public static function get_activity_by_id($activity_id){
        // get activity by id
	    $get_activity_by_id = DB::table('activities')->where('id', $activity_id)->get();

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

    public static function get_overview_members($activity_id){
        // get activity signup
        $get_activitie_signup = DB::table('activities_signup')
                ->where('activity_id', $activity_id)
                ->where('confirmation', 2)
                ->join('members', 'member_id', '=', 'members.id')
                ->join('users', 'members.user_id', '=', 'users.id')
                ->get();


        foreach($get_activitie_signup as $activity){
            $get_activitie_signup_intros = DB::table('activities_quest')
            ->where('activity_signup_id', $activity->signup_id)
            ->count();

            $activity->singup_intros = $get_activitie_signup_intros;

        }

        foreach($get_activitie_signup as $activity){
            $get_activitie_signup_intros = DB::table('activities_quest')
            ->where('activity_signup_id', $activity->signup_id)
            ->count();

            $price_members = ActivitiesSignup::get_price_members($activity_id);
            $price_intros = ActivitiesSignup::get_price_intros($activity_id);
            $amount = $price_intros * $get_activitie_signup_intros + $price_members;


            $activity->amount = $amount;
            $activity->singup_intros = $get_activitie_signup_intros;

        }


        // return activity signup to controller  
    	return $get_activitie_signup;
    }


    public static function check_active_activity($activity_id){
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
        $action = $_POST['submit'];

        unset($_POST['submit']);unset($_POST['id']);unset($_POST['_token']);

        if (empty($_POST)){
            Session::flash('feedback_error', "niemand geselecteerd");
            return false;
        }
        elseif ($action == "Ja" || $action == "Nee"){
            ($action=="Ja")?$action=1:$action=0;
            foreach ($_POST as $POST) {               
                DB::table('activities_signup')->where('signup_id', $POST)->update([
                    'paid' => $action,
                ]);
            }
            Session::flash('feedback_success', "veranderingen zijn gelukt!");
            return true;
        }

        elseif ($action=="Betaal herinnering"){
            foreach($_POST as $POST){

                $member_id = DB::table('activities_signup')
                ->where('signup_id', $POST)
                ->value('member_id');

                $fullname = Members::get_fullname_by_id($member_id);

                $get_user_id = DB::table('members')
                ->where('id', $member_id)
                ->value('user_id');

                $user_email = DB::table('users')
                ->where('id', $get_user_id)
                ->value('email');

                $get_activitie_signup_intros = DB::table('activities_quest')
                ->where('activity_signup_id', $POST)
                ->count();

                $price_members = ActivitiesSignup::get_price_members($activity_id);
                $price_intros = ActivitiesSignup::get_price_intros($activity_id);
                $amount = $price_intros * $get_activitie_signup_intros + $price_members;

                $activity_name = Activities::get_activity_name($activity_id);

                mail::send('email.payreminder',compact('fullname','amount','activity_name','user_email'), function($message)
                use ($user_email)
                {
                    $message->to($user_email, 'Allegria')->subject('Betaalbevestiging');

                });

            }
            Session::flash('feedback_success', "Het verzenden is gelukt!");
            return true;

        }
        elseif ($action=="Annulering"){
            /*$mail = new Mail();
            $database = DatabaseFactory::getFactory()->getConnection();
            foreach ($POST as $key => $value) {    
                $sql = "SELECT name FROM activities WHERE id = :id";
                $query = $database->prepare($sql);
                $query->execute(array(':id' => $activity_id));
                $activity = $query->fetch();

                $sql = "SELECT user_email FROM activities_signup  LEFT JOIN members ON activities_signup.member_id = members.id LEFT JOIN users ON members.user_id = users.user_id WHERE activities_signup.id = :id";
                $query = $database->prepare($sql);
                $query->execute(array(':id' => $value));
                $result = $query->fetch();

                if (!$mail->sendMailWithPHPMailer($result->user_email, Text::get('MAIL_FORGETPASSWORD_RECEIVER'), Text::get('MAIL_FORGETPASSWORD_NAME'), 'Afmelding voor '.$activity->name,
                    'U bent afgemeld voor de activiteit: '.$activity->name.'.', true)):
                    Session::add('feedback_negative', "iets mis gegaan");
                    return false;
                endif;

                $sql = "UPDATE activities_signup SET confirmation = 0 WHERE id = :id LIMIT 1";
                $query = $database->prepare($sql);
                $query->execute(array(':id' => strip_tags($value)));

                if ($query->rowCount()!=1):
                    Session::add('feedback_negative', "iets mis gegaan");
                    return false;
                endif;
            }
            Session::add('feedback_positive', "Het verzenden is gelukt!");
            return true;
        endif;*/
        }
    }
  
}   
