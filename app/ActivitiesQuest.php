<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
