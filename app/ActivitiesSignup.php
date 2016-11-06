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
}
