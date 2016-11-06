<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

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

    public static function get_active_activities($id){
    	$active_activities = DB::table('activities')->where('status', 1)->get();
    	return $active_activities;
    }

    public static function get_all_activities($id){
    	$all_activities = DB::table('activities')->get();
    	return $all_activities;
    }

    public static function get_signed_up_activities($id){
	    $signed_up_activities = DB::table('activities_signup')
	            ->join('activities', 'activity_id', '=', 'activities.id')
	       		->where('member_id', $id)
	            ->get();
        return $signed_up_activities;
    }

    public static function addACTION(){
		$new_activitie = Activities::create([
			'name' => $_POST['name'],
			'max_members' => $_POST['max_members'],
			'status' => $_POST['status'],
			'date' => $_POST['date'],
			'max_intros' => $_POST['max_intros'],
			'bus_boarding_point' => $_POST['bus_boarding_point'],
			'bus_amount' => $_POST['bus_amount'],
			'max_signup_date' => $_POST['max_signup_date'],
			'price_members' => $_POST['price_members'],
			'price_intros' => $_POST['price_intros'],
			'comments' => $_POST['comments'],
			'max_reserves' => $_POST['max_reserves'],
		]);
    }

    public static function get_activitie_by_id($id){
	    $get_activitie = DB::table('activities')->where('id', $id)->get();
        return $get_activitie;
    }

    public static function get_activitie_signup($id){
    	$get_activitie_signup = DB::table('activities_signup')->where('activity_id', $id)->get();
    	return $get_activitie_signup;
    }
}
