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

    public static function get_fullname(){
        $user_id = Auth::user()->id;
        $get_member = User::get_member();

        foreach($get_member as $member){
            $firstname = $member->firstname;
            $lastname = $member->lastname;
            $insertion = $member->insertion;
        }

        if(empty($insertion)){
            $fullname = "" .$firstname. " " .$lastname. "";
        }
        else{
            $fullname = "" .$firstname. " " .$insertion. " " .$lastname. "";
        }
       
        return $fullname;
    }   

    public static function get_user(){
       $user_id = Auth::user()->id;
       $get_user = DB::table('users')->where('id', $user_id)->get();
       return $get_user;
    } 

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

    public static function edit_account(){
        $user_id = Auth::user()->id;
        DB::table('members')->where('user_id', $user_id)->update([
            'housenumber' => $_POST['housenumber'],
            'zipcode' => $_POST['zipcode'],
            'place' => $_POST['place'],
            'location_building' => $_POST['location_building'],
            'location_floor' => $_POST['location_floor'],
            'phonenumber' => $_POST['phonenumber'],
      ]);

    }
    
}
