<?php

namespace App;

use DB;

use Session;

use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Override required, otherwise existing Authentication system will not match credential
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;

    protected $table = 'users';
    // define fillable columns
    protected $fillable = [ 
        'email',
        'user_creation_timestamp',
        'user_activation_hash',
        'user_account_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // define hidden columns
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function get_fullname(){
        // get member
        $get_member = User::get_member();

        // create separate variables
        foreach($get_member as $member){
            $firstname = $member->firstname;
            $lastname = $member->lastname;
            $insertion = $member->insertion;
        }

        // check if insertion is empty if so then create fullname without insertion
        if(empty($insertion)){
            $fullname = "" .$firstname. " " .$lastname. "";
        }
        // else create fullname with insertion
        else{
            $fullname = "" .$firstname. " " .$insertion. " " .$lastname. "";
        }

        // return fullname
        return $fullname;
    }   

    public static function get_user(){
       // get user id
       $user_id = Auth::user()->id;
       // get user with user id
       $get_user = DB::table('users')->where('id', $user_id)->get();
       // return user
       return $get_user;
    } 

    public static function get_member(){
        // get user id
        $user_id = Auth::user()->id;
        // get member with user id
        $get_member =  DB::table('users')
            ->join('members', 'users.id', '=', 'members.user_id')
            ->where('user_id', $user_id)
            ->get();

        // return member
        return $get_member;
    }

    public static function get_member_id(){
        // get user id
        $user_id = Auth::user()->id;
        // get member
        $get_member_id =  DB::table('users')
            ->join('members', 'users.id', '=', 'members.user_id')
            ->where('user_id', $user_id)
            ->get();

        // create member id
        foreach($get_member_id as $member){
            $member_id = $member->id;
        }
        // return member id
        return $member_id;
    }

    public static function edit_account(){
        // get user id
        $user_id = Auth::user()->id;
        // check if zipcode is valid
        if (preg_match("/^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/i", $_POST['zipcode'])){
            // if zipcode is not valid add error message to session
            Session::flash('feedback_error', 'Vul een geldig postcode in');
        }
        else{
            // edit account of user
            $query = DB::table('members')->where('user_id', $user_id)->update([
                'housenumber' => $_POST['housenumber'],
                'zipcode' => $_POST['zipcode'],
                'place' => $_POST['place'],
                'location_building' => $_POST['location_building'],
                'location_floor' => $_POST['location_floor'],
                'phonenumber' => $_POST['phonenumber'],
            ]);
            // check if query was succesfull
            if(count($query) == 1){
                // add succes message to session
                Session::flash('feedback_succes', 'Gegevens zijn gewijzigd!');
            }
            else{
                // add error message to session
                Session::flash('feedback_error', 'Er is iets mis gegaan');
            }
        }
    }

    public static function edit_password(){
        // get user id
        $user_id = Auth::user()->id;
        // check if zipcode is valid
        $password_new = $_POST['password_new'];
        $password_repeat = $_POST['password_repeat'];
        if(!Members::editPasswordValidation($password_new,$password_repeat)){
            Session::flash('feedback_error', 'Paswoorden zijn niet gelijk');
            return false;
        }
       
        $query = DB::table('users')->where('id', $user_id)->update([
            'password' => bcrypt($_POST['password_new']),
        ]);

        Session::flash('feedback_success', 'Paswoord opgeslagen!');
        return true;
    } 
}
