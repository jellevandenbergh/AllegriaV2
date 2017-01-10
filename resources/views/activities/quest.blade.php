@include('layouts.header')
<div class="container">
    <h1><?=(empty($get_activity_by_id)?"Activiteit niet gevonden":"Introducés voor: ".$get_activity_name)?></h1>
    <div class="box">
    @include('layouts.feedback')
@if(empty($get_activity_by_id))
<p class="red-text">Activiteit niet gevonden.</p>
            <br>
            <p><a href="activities" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a></p>
@else
<!--@if($intros_count >= $get_max_intros)
<div class="feedback error"><strong>Deze activiteit zit vol! Opgeven is nog steeds mogelijk voor de wachtlijst.</strong></div>
@endif-->
            <form method="post" action="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <h3>Informatie activiteit:</h3>
                <table class="table activities-signup single">
                    <thead>
                        <tr>
                            <td colspan="2">Algemeen</td>
                            <td colspan="2">Deelnemers</td>
                            <td colspan="2">Overige</td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($get_activity_by_id  as $activity)
                        <tr>
                            <td>Naam</td>
                            <td>{{ $activity->name }}</td>
                            <td>Max introducés</td>
                            <td>{{ $activity->max_intros }}</td>
                            <td>Vrije plekken</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Datum activiteit</td>
                            <td>{{ $activity->date }}</td>
                            <td>Prijs per introducé</td>
                            <td>{{ $activity->price_intros }}</td>
                            <td colspan="2"><?= (($activity->comments)?"Opmerkingen":"Geen opmerkingen")?></td>
                        </tr>
                        <tr>
                            <td>Uiterste inschrijfdatum</td>
                            <td>{{ $activity->max_signup_date }}</td>
                            <td>Prijs per lid</td>
                            <td>{{ $activity->price_members }}</td>
                            <td colspan="2">{{ $activity->comments }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="hr"></div>

                <h3>Introducés:</h3>
                <p>Bij elke nieuwe introducé zijn een naam en geboortedatum verplicht.</p>
                <table class="table activities-quest single">
                    <thead>
                        <tr>
                            <td>Naam</td>
                            <td>Geboortedatum</td>
                            <td>Opmerkingen</td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($get_intros_by_id as $intros)
                        <tr>
                            <td>{{ $intros->name }}</td>
                            <td>{{ $intros->birthday }}</td>
                            <td>{{ $intros->comments }}</td>
                        </tr>
                    @endforeach
                        <!-- <tr>
                            <td colspan="3"></td>
                        </tr> -->
                    <?php for ($i=$intros_count + 1; $i <= $get_max_intros; $i++){ ?>
                        <tr>
                            <td><input type="text" class="intros" name="name-intro-<?=$i?>" placeholder="Naam"></td>
                            <td><input type="date" name="birthday-intro-<?=$i?>" placeholder="dd-mm-jjjj"></td>
                            <td><textarea name="comments-intro-<?=$i?>" placeholder="Niet verplicht"></textarea></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
<?php if($intros_count >= $get_max_intros): ?>
                <p>Je kunt geen introducés meer toevoegen, omdat je het maximale aantal bereikt hebt van: <strong>{{ $get_max_intros }} introducés</strong>.</p>
                <div class="hr"></div>
                <p><a href="activities" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a> <a href="activities/signout/".$activity_id."" class="allegriabutton">Introducés uitschrijven <i class="fa fa-external-link"></i></a></p>
<?php else: ?>

                <p>Totaal bedrag: <strong id="price"></strong></p>
                <p><input id="agree" name="agree" type="checkbox" required><label for="agree">Ik ga akkoord met het <a href="<?php echo Config::get('URL');?>download/reglement.pdf" target="_blank">reglement</a>.</label></p>
                <p><input type="submit" name="submit" value="Opslaan"> <a href="activities" class="allegriabutton">Annuleren</a> <a href="activities/signout/".$activity_id."" class="allegriabutton">Introducés uitschrijven <i class="fa fa-external-link"></i></a></p>
            </form>
<?php endif; ?>
        @endif
    </div>
</div>
<script>
    $("input").blur(function(){
        var count = 0;
        $(".intros").each(function(){
            var text = $(this).val();
            if (text > ' ') {
                count = count + 1;
            };
        });
        calculatePrice(count);
    });
    function calculatePrice(count)
    {
        var memberPrice = <?=$get_price_members ?>;
        var introsPrice = <?=$get_price_intros ?>;
        var introsCount = <?=$intros_count ?> + count;
        var totalPrice = (memberPrice + (introsPrice * introsCount));
        $("#price").text("€"+(totalPrice/100).toFixed(2).toString().replace(".", ","));
    }
</script>
@include('layouts.footer')