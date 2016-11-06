<?php

namespace App;

use DB;
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



    public static function get_member($id){
        $get_member =  DB::table('users')
            ->join('members', 'users.id', '=', 'members.user_id')
            ->where('user_id', $id)
            ->get();
        return $get_member;
    }
}
