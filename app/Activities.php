<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

use Session;

class Activities extends Model
{
	protected $table = 'activities';

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

    public static function freePlace($àctivity_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT count(*) AS count FROM activities_signup WHERE activity_id = :id AND status = 1";
        $query = $database->prepare($sql);
        $query->execute(array(":id"=>$id));

        

        $result_signup = $query->fetch();

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT count(*) AS count FROM activities_quest LEFT JOIN activities_signup ON activities_quest.activity_signup_id = activities_signup.id WHERE activity_id = :id AND activities_quest.status = 1";
        $query = $database->prepare($sql);
        $query->execute(array(":id"=>$id));

        $result_quest = $query->fetch();

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT max_members FROM activities WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute(array(":id"=>$id));

        $result_max_members = $query->fetch();

        $result = $result_max_members->max_members - ($result_signup->count + $result_quest->count);

        if ($result <= 0):
            $result = "Vol/Wachtlijst";
        endif;

        return $result;
    }


    public static function get_active_activities(){
    	$active_activities = DB::table('activities')->where('status', 2)->get();
    	return $active_activities;
    }

    public static function get_all_activities(){
    	$all_activities = DB::table('activities')->get();
    	return $all_activities;
    }

    public static function get_signed_up_activities(){
    	$member_id = User::get_member_id();
	    $signed_up_activities = DB::table('activities_signup')
	            ->join('activities', 'activity_id', '=', 'activities.id')
	       		->where('member_id', $member_id)
	            ->get();
        return $signed_up_activities;
    }

    public static function addACTION(){
    	$_POST['price_members'] = str_replace(array(',', '.'), '',$_POST['price_members']);
        $_POST['price_intros'] = str_replace(array(',', '.'), '',$_POST['price_intros']);

        if ($_POST['max_intros'] == 0) {
            $_POST['price_intros'] = 0;
        }

        if( $_POST['bus'] == '1'){
        	$_POST['bus_boarding_point'] = '';
        	$_POST['bus_amount'] = 0;
        }

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

		$new_activitie_id = $new_activitie->id;

		$get_new_activitie = DB::table('activities')->where('id', $new_activitie_id)->get();

		if ($get_new_activitie == "[]") {
		    Session::flash('feedback_error', 'Er is iets mis gegaan!');
		}
		else{
			$server = ($_SERVER["SERVER_NAME"]);
			$signupUrl =  $server .'/AllegriaV2/public/activities/signup/'. $new_activitie->id;

			Session::flash('feedback_success', 'Activiteit toegevoegd! De link voor het aanmelden van deze activiteit is: '.$signupUrl.'');
		}

    }

    public static function get_activitie_by_id($activity_id){
	    $get_activitie = DB::table('activities')->where('id', $activity_id)->get();
        return $get_activitie;
    }

    public static function get_activitie_signup($activity_id){
        $get_activitie_signup = DB::table('activities_signup')
                ->join('activities', 'activity_id', '=', 'activities.id')
                ->join('members', 'member_id', '=', 'members.id')
                ->get();
    	return $get_activitie_signup;
    }





















































  
}
