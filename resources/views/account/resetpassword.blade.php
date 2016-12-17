@include('layouts.header')
<div class="container">
    <h1>Nieuw wachtwoord</h1>
    <div class="box">
    @include('layouts.feedback')
        <div class="allegria-editpassword">
            <form action="" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <label>Een wachtwoord moet minimaal 6 karakaters bevatten.</label>
                <li><i class="fa fa-key"></i><input type="password" name="password_new" pattern=".{6,}" placeholder="Nieuw wachtwoord" required autocomplete="off" autofocus /></li>
                <li><i class="fa fa-key"></i><input type="password" name="password_repeat" pattern=".{6,}" placeholder="Wachtwoord herhalen" required autocomplete="off" /></li>
                <input type="submit" class="login-submit-button" value="Opslaan"/> <a href="<?php echo Config::get('URL');?>" class="allegriabutton">Annuleren</a>
            </form>
        </div>
    </div>
</div>
@include('layouts.footer')