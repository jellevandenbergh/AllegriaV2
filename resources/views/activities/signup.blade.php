@include('layouts.header')
<div class="container">
    <h1>{{ $get_activitie_name }}</h1>
    <div class="box">
        @include('layouts.feedback')
   
            <form method="post" action="{{$activity_id}}">
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
                    @foreach($get_activitie as $activitie)
                    <tbody>
                       <tr>
                        <td>Naam</td>
                        <td>{{ $activitie->name }}</td>
                        <td>Max introducés</td>
                        <td>{{ $activitie->max_intros }}</td>
                        <td>Vrije plekken</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Datum activiteit</td>
                        <td>{{ $activitie->date }}</td>
                        <td>Prijs per introducé</td>
                        <td>{{ $activitie->price_intros }}</td>
                        <td colspan="2"><?= (($activitie->comments)?"Opmerkingen":"Geen opmerkingen")?></td>
                    </tr>
                    <tr>
                        <td>Uiterste inschrijfdatum</td>
                        <td>{{ $activitie->max_signup_date }}</td>
                        <td>Prijs per lid</td>
                        <td>{{ $activitie->price_members }}</td>
                        <td colspan="2">{{ $activitie->comments }}</td>
                    </tr>
                    </tbody>
                    @endforeach
                </table>

                <div class="hr"></div>
                <h3>Eigen gegevens:</h3>
                <table class="table activities-signup single">
                    <thead>
                        <tr>
                            <td colspan="2">Gegevens</td>
                            <td colspan="2">Overige</td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($get_member as $member)
                        <tr>
                            <td>Naam</td>
                            <td>{{ $fullname }}</td>
                            <td>Aantal introducés</td>
                            <td>
<?php for ($i=0; $i <= $get_max_intros; $i++) { ?>
                            <input type="radio" id="intros-<?=$i?>" name="max_intros" value="<?=$i?>" <?= (($i==0)?"checked":"")?>><label for="intros-<?=$i?>"><?=$i?></label><?= (($i==5)?"<br>":"")?>
<?php } ?>       
                            </td>
                        </tr>
                        <tr>
                            <td>Geboortedatum</td>
                            <td>{{ $member->birthday }}</td>
                            <td colspan="2">Opmerkingen</td>
                            
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><label for="comments">{{ $member->email }}</label></td>
                            <td colspan="2" rowspan="2"><textarea name="comments" placeholder="Niet verplicht"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="place">Opstapplaats</label></td>
                            <td><select name="place"><option value="LPP">LPP</option><option value="MBW">MBW</option></select></td>
                        </tr>
                    </tbody>
                    @endforeach
                </table>

                 <div id="divintros" class="display-none">
                    <div class="hr"></div>
                    <h3>Introducés:</h3>
                    <table id="tableintros" class="table activities-signup single">
                        <thead>
                            <tr>
                                <td colspan="2">Gegevens</td>
                                <td colspan="2">Overige</td>
                            </tr>
                        </thead>

                    </table>
                </div>

                <div class="hr"></div>

                <p>Totaal bedrag: <strong id="price"></strong></p>
                <p><input id="agree" name="agree" type="checkbox" required><label for="agree">Ik ga akkoord met het <a href="<?php echo Config::get('URL');?>download/reglement.pdf" target="_blank">reglement</a>.</label></p>
                <p><input type="submit" name="submit" value="Inschrijven"> <a href="<?php echo Config::get('URL');?>activities" class="allegriabutton">Annuleren</a></p>
            </form>
            <br>
            <p><a href="" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a></p>
    </div>
</div>
<script>
    $(function(){
        $("input[name=max_intros]").change(function () {
            if(document.getElementById("intros-0").checked) {
                $('#divintros').addClass('display-none');
            } else {
                $('#divintros').removeClass('display-none');
            }
            makeTable();
            calculatePrice();
        });
    });

    function calculatePrice()
    {
        var memberPrice = <?=$get_price_members ?>;
        var introsPrice = <?=$get_price_intros ?>;
        var introsCount = $("input[name=max_intros]:checked").val();
        var totalPrice = (memberPrice + (introsPrice * introsCount));
        $("#price").text("€"+(totalPrice/100).toFixed(2).toString().replace(".", ","));
    }

    function makeTable()
    {
        var value = parseInt($("input[name=max_intros]:checked").val());
        if (value > '0') {
        var tablestring = '<thead><tr><td colspan="2">Gegevens</td><td colspan="2">Overige</td></tr></thead><tbody>';
        for (var i = 1; i <= value; i++) {
            tablestring += '<tr class="intro-'+i+'"><td>Naam '+i+'</td><td><input type="text" name="name-intro-'+i+'" placeholder="Verplicht" required></td><td colspan="2">Opmerkingen '+i+'</td>';
            tablestring += '<tr class="intro-'+i+'"><td>Geboortedatum '+i+'</td><td><input type="date" name="birthday-intro-'+i+'" placeholder="dd-mm-jjjj" required></td><td colspan="2"><textarea name="comments-intro-'+i+'" placeholder="Niet verplicht"></textarea></td></tr>';
        }
        tablestring += '</tbody>';
        } else {
            var tablestring = "";
        }
        $('#tableintros').html(tablestring);
    }
</script>

@include('layouts.footer')