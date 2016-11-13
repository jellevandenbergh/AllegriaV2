<?php

namespace App;

use DB;

use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'email', 
        'password',
        'RNRnumber',
        'lastname',
        'initials',
        'insertion',
        'salutation',
        'firstname',
        'address',
        'housenumber',
        'zipcode',
        'place',
        'birthday',
        'location_building',
        'location_floor',
        'member_since',
        'phonenumber',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



    public static function get_member(){
        $user_id = Auth::user()->id;
        $get_member =  DB::table('users')
            ->join('members', 'users.id', '=', 'members.user_id')
            ->where('user_id', $user_id)
            ->get();

        return $get_member;
    }

    public static function get_member_id(){
        $user_id = Auth::user()->id;
        $get_member_id =  DB::table('users')
            ->join('members', 'users.id', '=', 'members.user_id')
            ->where('user_id', $user_id)
            ->get();

        foreach($get_member_id as $member){
            $member_id = $member->id;
        }

        return $member_id;
    }
    
}
