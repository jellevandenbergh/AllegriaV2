@include('layouts.header')
<div class="container">
    <h1>{{ $activity_name }}</h1>
    <span class="clear"></span>
    <div class="box">
    @include('layouts.feedback')
        <form method="post" action="" class="allegriaform">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <p>Weet je zeker dat je de activiteit <b>{{ $activity_name }}</b> wilt verwijderen met alle bijbehoorende inschrijvingen?</p>
            <p><input type="submit" name="submit" value="Verwijderen"> <input type="submit" name="submit" value="Annuleren"></p>
    </div>
</div>
@include('layouts.footer')