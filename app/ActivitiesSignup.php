<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

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

    

    public static function signupACTION($id)
    {
        $user_id = Auth::user()->id;
        $member_id = User::get_member_id($user_id);
        $new_activitie_signup = ActivitiesSignup::create([
            'activity_id' => $id,
            'member_id' => $member_id,
            'place' => $_POST['place'],
            'comments' => $_POST['comments'],
        ]);
    }

}
