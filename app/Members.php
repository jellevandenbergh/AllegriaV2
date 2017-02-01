<?php

namespace App;

use DB;

use Session;
use Auth;

use App\User;
use Mail;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    public $timestamps = false;

    protected $table = 'members';

    protected $fillable = [ 
        'email',
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
        'user_id',
    ];


	public static function get_fullname_by_id($member_id){
        // get member
        $get_member = Members::get_member($member_id);

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

    public static function get_all_members(){
    	// get all members
        $members =  DB::table('users')
            ->join('members', 'users.id', '=', 'members.user_id')
            ->select('lastname','initials', 'insertion', 'salutation', 'firstname', 'address', 'housenumber', 'zipcode', 'place', 'location_building', 'location_floor', 'phonenumber', 'email', 'user_account_type', 'birthday','members.id','location_building','location_floor','phonenumber','status','RNRnumber','member_since') 
            ->get();

        foreach ($members as $member) {
            if($member->user_account_type == 1){
                $member->user_account_type = 'lid';
            }
            elseif($member->user_account_type == 2){
                $member->user_account_type = 'bestuur';
            }
            elseif($member->user_account_type == 3){
                $member->user_account_type = 'admin';
            }
            elseif($member->user_account_type == 4){
                $member->user_account_type = 'super admin';
            }
        }
        return $members;
    }

    public static function get_member($member_id){
    	// get all members
        $get_member =  DB::table('members')
            ->join('users', 'members.user_id', '=', 'users.id')
            ->where('members.id', $member_id)
            ->get();
        // return member
        return $get_member;
    }

    public static function add_member(){

        $check_email = DB::table('users')->where('email', $_POST['email'])->get();
        if (count($check_email) ==  1) {
            Session::flash('feedback_error', 'Email bestaat al!');
            exit;
        }

    	$token = hash_hmac('sha256', str_random(40), config('app.key'));

        $new_user = User::create([
            'email' => $_POST['email'],
            'user_account_type' => $_POST['function'],
            'user_creation_timestamp' => time(),
            'user_activation_hash' => $token,
        ]);

        if (count($new_user) !=  1) {
             Session::flash('feedback_error', 'Account maken niet gelukt!');
             return false;
             exit;
        }

        // create new member
        $new_member = Members::create([
            'RNRnumber' => $_POST['RNRnumber'],
            'lastname' => $_POST['lastname'],
            'initials' => $_POST['initials'],
            'insertion' => $_POST['insertion'],
            'salutation' => $_POST['salutation'],
            'firstname' => $_POST['firstname'],
            'address' => $_POST['address'],
            'housenumber' => $_POST['housenumber'],
            'zipcode' => $_POST['zipcode'],
            'place' => $_POST['place'],
            'birthday' => $_POST['birthday'],
            'location_building' => $_POST['location_building'],
            'location_floor' => $_POST['location_floor'],
            'member_since' => $_POST['member_since'],
            'phonenumber' => $_POST['phonenumber'],
            'user_id' => $new_user->id,
            'edit_timestamp' => date('Y-m-d H:i:s'),
        ]);


        if (count($new_member) !=  1) {
            // add error message to session
            Session::flash('feedback_error', 'Account maken niet gelukt!');
            // delete created user
            DB::table('users')->where('id', $new_user->id)->delete();
            exit;
        }
        $server = ($_SERVER["SERVER_NAME"]);
        // create signup url
        $activationlink =  $server .'/AllegriaV2/public/account/newuser/'.$token;
        $fullname = Members::get_fullname_by_id($new_member->id);

        // send mail
        /*mail::send('email.newmember',compact('fullname','activationlink'), function($message)
        {
            $message->to($_POST['email'], 'Allegria')->subject('Account activeren');

        });*/


        Session::flash('feedback_success', 'Lid toegevoegd! Activeerlink is:'.$activationlink.'');
        

        // end send mail
    }

    public static function delete_member($member_id){
        $check_user_id = DB::table('members')->where('id', $member_id)->value('user_id');
        if (!count($check_user_id) ==  1) {
            Session::flash('feedback_error', 'Lid niet gevonden');
            return flase;
        }

        DB::table('users')->where('id', $check_user_id)->delete();
        DB::table('members')->where('id', $member_id)->delete();
        $activities_signup_id = DB::table('activities_signup')->where('member_id', $member_id)->pluck('id');
        foreach($activities_signup_id as $id){
             DB::table('activities_quest')->where('activity_signup_id', $id)->delete();
        }
        DB::table('activities_signup')->where('member_id', $member_id)->delete();
        Session::flash('feedback_success', 'Lid verwijderd');
        return true;
    }

    public static function forgot_password(){

        $check_email = DB::table('users')->where('email', $_POST['email'])->get();
        if (!count($check_email) ==  1) {
            Session::flash('feedback_error', 'Email niet gevonden!');
            return false;
        }

        foreach($check_email as $check){
            $user_password_reset_timestamp = $check->user_password_reset_timestamp;
        }

        $timestamp = time() - (int)$user_password_reset_timestamp;

        $time_left = 300 - $timestamp;

        if($timestamp < 250){
            Session::flash('feedback_error', 'Wacht '. $time_left .' seconden voordat u het opnieuw probeert');
            return false;
        }
        
        $token = hash_hmac('sha256', str_random(40), config('app.key'));

        DB::table('users')->where('email', $_POST['email'])->update([
            'user_forgot_password_token' => $token,
            'user_password_reset_timestamp' => time(),
        ]);

        $server = ($_SERVER["SERVER_NAME"]);

        $activationlink =  $server .'/AllegriaV2/public/forgotpassword/'.$token;
        
        // send mail
        mail::send('email.forgotpassword',compact('activationlink'), function($message)
        {
            $message->to($_POST['email'], 'Allegria')->subject('Paswoord vergeten');

        });
        // end send mail
        Session::flash('feedback_success', 'Er is een email gestuurd om uw wachtwoord opnieuw in te stellen');
        return true;
    }

    public static function reset_password_by_token($token){

        $user_password_reset_timestamp = DB::table('users')->where('user_forgot_password_token', $token)->value('user_password_reset_timestamp');
        if (!count($user_password_reset_timestamp) ==  1) {
            return false;
            exit;
        }

        $timestamp = time() - (int)$user_password_reset_timestamp;

        if ($timestamp > 3600){
            Session::flash('feedback_error', 'Link is verstreken.. vraag een nieuwe link aan');
            return false;
        }

        $password_new = $_POST['password_new'];
        $password_repeat = $_POST['password_repeat'];
        if(!Members::editPasswordValidation($password_new,$password_repeat)){
            Session::flash('feedback_error', 'Paswoorden zijn niet gelijk');
            return false;
        }

        $query = DB::table('users')->where('user_forgot_password_token', $token)->update([
            'password' => bcrypt($_POST['password_new']),
            'user_forgot_password_token' => NULL,
        ]);

        Session::flash('feedback_success', 'Paswoord resetten gelukt!');
        return true;
        
        // send mail
        // end send mail
    }


    public static function editPasswordValidation($password_new, $password_repeat) {
        if (empty($password_new) OR empty($password_repeat)) {
            //Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
            return false;
        } else if ($password_new !== $password_repeat) {
            //Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
            return false;
        } else if (strlen($password_new) < 6) {
           // Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
           return false;
        } else {
            return true;
        }
        return false;
    }

    public static function new_user_set_new_password($token){
        $user_creation_timestamp = DB::table('users')->where('user_activation_hash', $token)->value('user_creation_timestamp');
        if (!count($user_creation_timestamp) ==  1) {
            return false;
            exit;
        }

        $timestamp = time() - (int)$user_creation_timestamp;

        if ($timestamp > 86400){
            Session::flash('feedback_error', 'Link is verstreken.. vraag een nieuwe link aan');
            return false;
        }

        $password_new = $_POST['password_new'];
        $password_repeat = $_POST['password_repeat'];
        if(!Members::editPasswordValidation($password_new,$password_repeat)){
            Session::flash('feedback_error', 'Paswoorden zijn niet gelijk');
            return false;
        }
        $query = DB::table('users')->where('user_activation_hash', $token)->update([
            'password' => bcrypt($_POST['password_new']),
            'user_activation_hash' => NULL,
            'user_activated' => 2,
        ]);
        Session::flash('feedback_success', 'Wachtwoord opgeslagen, Log nu hier in');
        return true;

    }

    public static function check_activate_token($token){
        $check_token = DB::table('users')->where('user_activation_hash', $token)->get();
        if (count($check_token) !=  1) {
            //Session::flash('feedback_success', 'Account niet gevonden!');
            return false;
        }
        else{
            return true;
        }
    }

    public static function sendverification($member_id){
        $user_id = DB::table('members')->where('id', $member_id)->value('user_id');
        $user_creation_timestamp = DB::table('users')
            ->where('id', $user_id)
            ->where('user_activated', 1)
            ->value('user_creation_timestamp');
        if (!count($user_creation_timestamp) ==  1) {
            Session::flash('feedback_error', 'Lid is al geverifieerd');
            return false;
        }

        $timestamp = time() - (int)$user_creation_timestamp;

        if ($timestamp < 86400){
            Session::flash('feedback_error', 'Link is al minder dan 1 dag geleden verstuurd');
            return false;
        }

        $token = hash_hmac('sha256', str_random(40), config('app.key'));

        $query = DB::table('users')->where('id', $user_id)->update([
            'user_activation_hash' => $token,
            'user_creation_timestamp' => time(),
        ]);

        $server = ($_SERVER["SERVER_NAME"]);
        
        $activationlink =  $server .'/AllegriaV2/public/account/newuser/'.$token;
        Session::flash('feedback_success', 'Activeer link is: ' .$activationlink.'');
        return true;

    }
    public static function check_reset_password_token($token){
        $user_password_reset_timestamp = DB::table('users')->where('user_forgot_password_token', $token)->value('user_password_reset_timestamp');
        if (count($user_password_reset_timestamp) !=  1) {
            //Session::flash('feedback_succes', 'Account niet gevonden!');
            return false;
        }
        else{
            return true;
            //return $user_password_reset_timestamp;
        }
    }

    public static function edit_member($member_id){
    	$query = DB::table('members')->where('id', $member_id)->update([
            'RNRnumber' => $_POST['RNRnumber'],
            'lastname' => $_POST['lastname'],
            'initials' => $_POST['initials'],
            'insertion' => $_POST['insertion'],
            'salutation' => $_POST['salutation'],
            'firstname' => $_POST['firstname'],
            'address' => $_POST['address'],
            'housenumber' => $_POST['housenumber'],
            'zipcode' => $_POST['zipcode'],
            'place' => $_POST['place'],
            'birthday' => $_POST['birthday'],
            'location_building' => $_POST['location_building'],
            'location_floor' => $_POST['location_floor'],
            'member_since' => $_POST['member_since'],
            'phonenumber' => $_POST['phonenumber'],
            'edit_timestamp' => date('Y-m-d H:i:s'),
        ]);
        // check if query was succesfull
        if(count($query) == 1){
            // add succes message to session
            Session::flash('feedback_success', 'Gegevens zijn gewijzigd!');
            return true;
        }
        else{
            // add error message to session
            Session::flash('feedback_error', 'Er is iets mis gegaan');
            return false;
        }

    }
 
}
