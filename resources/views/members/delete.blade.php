@include('layouts.header')
<div class="container">
    <h1><?php if(!$fullname): ?> Lid niet gevonden <?php else: ?> {{$fullname}}<?php endif; ?></h1>
    <span class="clear"></span>
    <div class="box">

        @include('layouts.feedback')

        @if(!$member)
            <p class="red-text">Lid niet gevonden.</p>
            <br>
            <p><a href="members" class="allegriabutton">Terug</a></p>
        @else
            <form method="post" action="" class="allegriaform">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <p>Weet je zeker dat je <b>{{ $fullname }}</b> wilt verwijderen met alle bijbehoorende inschrijvingen?</p>
            <p><input type="submit" name="submit" value="Verwijderen"> <a href="members" class="allegriabutton">Annuleren</a></p>
        </form>
        @endif
    </div>
</div>
@include('layouts.footer')