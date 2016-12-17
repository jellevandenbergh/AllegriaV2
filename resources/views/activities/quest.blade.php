@include('layouts.header')
<div class="container">
    <h1><?=(($this->signup && $this->activity)?"Introducés voor: ".htmlentities($this->activity->name):"Activiteit niet gevonden")?></h1>
    <div class="box">
    @include('layouts.feedback')
        1<div class="feedback error"><strong>Deze activiteit zit vol! Opgeven is nog steeds mogelijk voor de wachtlijst.</strong></div>
            <form method="post" action="<?php echo Config::get('URL');?>activities/questACTION">
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
                        <tr>
                            <td>Naam</td>
                            <td><?= htmlentities($this->activity->name);?></td>
                            <td>Max introducés</td>
                            <td><?= htmlentities($this->activity->max_intros)?></td>
                            <td>Vrije plekken</td>
                            <td><?= htmlentities($this->activity->freePlace)?></td>
                        </tr>
                        <tr>
                            <td>Datum activiteit</td>
                            <td><?=Functions::formatDate($this->activity->date)?></td>
                            <td>Prijs per introducé</td>
                            <td><?=Functions::formatPrice($this->activity->price_intros)?></td>
                            <td colspan="2"><?= (($this->activity->comments)?"Opmerkingen":"Geen opmerkingen")?></td>
                        </tr>
                        <tr>
                            <td>Uiterste inschrijfdatum</td>
                            <td><?=Functions::formatDate($this->activity->max_signup_date)?></td>
                            <td>Prijs per lid</td>
                            <td><?=Functions::formatPrice($this->activity->price_members)?></td>
                            <td colspan="2"><?= htmlentities($this->activity->comments);?></td>
                        </tr>
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
<?php if(isset($this->signup->intros)): foreach($this->signup->intros as $key => $value): ?>
                        <tr>
                            <td><?=htmlentities($value->name)?></td>
                            <td><?=htmlentities($value->birthday)?></td>
                            <td><?=htmlentities($value->comments)?></td>
                        </tr>
<?php endforeach; endif; if($this->signup->count<$this->activity->max_intros): ?>
                        <!-- <tr>
                            <td colspan="3"></td>
                        </tr> -->
<?php for ($i=$this->signup->count; $i < $this->activity->max_intros; $i++): ?>
                        <tr>
                            <td><input type="text" class="intros" name="intro-<?=$i?>-name" placeholder="Naam"></td>
                            <td><input type="date" name="intro-<?=$i?>-birthday" placeholder="dd-mm-jjjj"></td>
                            <td><textarea name="intro-<?=$i?>-comments" placeholder="Niet verplicht"></textarea></td>
                        </tr>
<?php endfor; endif; ?>
                    </tbody>
                </table>
<?php if($this->signup->count>=$this->activity->max_intros): ?>
                <p>Je kunt geen introducés meer toevoegen, omdat je het maximale aantal bereikt hebt van: <strong><?=htmlentities($this->activity->max_intros)?> introducés</strong>.</p>
                <div class="hr"></div>
                <p><a href="<?php echo Config::get('URL');?>activities" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a> <a href="<?=Config::get('URL').'activities/signout/'.$this->activity->id?>" class="allegriabutton">Introducés uitschrijven <i class="fa fa-external-link"></i></a></p>
<?php else: ?>
                <div class="hr"></div>

                <p>Totaal bedrag: <strong id="price"><?=Functions::formatPrice($this->activity->price_members+($this->activity->price_intros*$this->signup->count))?></strong></p>

                <input type="hidden" name="activity_signup_id" value="<?=$this->signup->id?>"><input type="hidden" name="activity_id" value="<?=$this->activity->id?>"><input type="hidden" name="activity_signup_count" value="<?=$this->signup->count?>"><input type="hidden" name="activity_max_intros" value="<?=$this->activity->max_intros?>">
                <p><input id="agree" name="agree" type="checkbox" required><label for="agree">Ik ga akkoord met het <a href="<?php echo Config::get('URL');?>download/reglement.pdf" target="_blank">reglement</a>.</label></p>
                <p><input type="submit" name="submit" value="Opslaan"> <a href="<?php echo Config::get('URL');?>activities" class="allegriabutton">Annuleren</a> <a href="<?=Config::get('URL').'activities/signout/'.$this->activity->id?>" class="allegriabutton">Introducés uitschrijven <i class="fa fa-external-link"></i></a></p>
<?php endif; ?>
            </form>
        <?php else: ?>
            <p class="red-text">Activiteit niet gevonden.</p>
            <br>
            <p><a href="<?php echo Config::get('URL'); ?>activities" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a></p>
        <?php endif; ?>
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
        var memberPrice = <?=$this->activity->price_members?>;
        var introsPrice = <?=$this->activity->price_intros?>;
        var introsCount = <?=$this->signup->count?> + count;
        var totalPrice = (memberPrice + (introsPrice * introsCount));
        $("#price").text("€"+(totalPrice/100).toFixed(2).toString().replace(".", ","));
    }
</script>
@include('layouts.footer')