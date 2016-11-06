@include('layouts.header')

<div class="container">
    <h1>Inloggen</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        
        <div class="allegria-login">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <li><i class="fa fa-user"></i><input type="email" name="email" placeholder="Email" required autofocus /></li>
                <li><i class="fa fa-key"></i><input type="password" name="password" placeholder="Wachtwoord" required /></li>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <p><a href="">Wachtwoord vergeten?</a></p>
        </div>
    </div>
</div> 


@include('layouts.footer')

