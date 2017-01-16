@include('layouts.header')
<div class="container">
    <span class="clear"></span>
    <div class="box">
    @include('layouts.feedback')
        <form method="post" action="" class="allegriaform">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <p><input type="submit" name="submit" value="Verwijderen"> 
            <input type="submit" name="submit" value="Annuleren"></p>
    </div>
</div>
@include('layouts.footer')