@include('layouts.header')

<div class="container">
    <h1>Paswoord vergeten</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        @include('layouts.feedback')
        <div class="allegria-login">
            <form class="form-horizontal" role="form" method="POST" action="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <li><i class="fa fa-user"></i><input type="email" name="email" placeholder="Email" required autofocus /></li>
                <button type="submit" class="btn btn-primary">Verstuur</button>
            </form>
        </div>
    </div>
</div> 


@include('layouts.footer')