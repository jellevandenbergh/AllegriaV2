@include('layouts.header')
<style>
.display_none{
    display: none;
}
.table_row{
    display: table-row;
}


</style>
<div class="container">
    <h1>Activiteiten toevoegen</h1>
    <span class="clear"></span>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <form class="form-horizontal" role="form" method="POST" action="{{ url('http://localhost/AllegriaV2/public/activities/add') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                        <td><label for="name">Naam</label></td>
                        <td><input id="name" type="text" name="name" placeholder="Verplicht" required></td>
                        <td><label for="max_members">Max deelnemers</label></td>
                        <td><input id="max_members" type="number" name="max_members" placeholder="Verplicht" required></td>
                        <td><label>Actief</label></td>
                        <td id="bus_save"><input type="radio" id="status-1" name="status" value="1" > <label for="status-1">Nee</label> <input type="radio" id="status-2" name="status" value="2" checked> <label for="status-2">Ja</label></td>
                    </tr>
                    <tr>
                        <td><label for="date">Datum activiteit</label></td>
                        <td><input id="date" type="date" name="date" placeholder="dd-mm-jjjj" required></td>
                        <td><label for="max_intros">Max introducés</label></td>
                        <td><input id="max_intros" type="number" name="max_intros" value="0" required></td>
                        <td><label>Bus</label></td>
                        <td><input type="radio" id="bus-1" name="bus" value="1" onclick="bus_no()" checked> <label for="bus-1">Nee</label> <input type="radio" id="bus-2" name="bus" value="2" onclick="bus_yes()"> <label for="status-2">Ja</label></td>
                    </tr>
                    <tr class="display_none" id="bus_form">
                        <td>Opstapplaats</td>
                        <td><input id="bus_boarding_point" type="text" name="bus_boarding_point" placeholder="Verplicht"></td>
                        <td>Aantal bussen</td>
                        <td><input id="bus_amount" type="number" name="bus_amount" value="1"required></td>
                        <td></td>
                        <td style="cursor: pointer"><a  onclick="bus_save()">Opslaan</a></td>
                    </tr>
                    <tr>
                        <td><label for="max_signup_date">Uiterste inschrijfdatum</label></td>
                        <td><input id="max_signup_date" type="date" name="max_signup_date" placeholder="dd-mm-jjjj" required></td>
                        <td><label for="price_members">Prijs per lid</label></td>
                        <td><input id="price_members" type="text" name="price_members" placeholder="00,00" required></td>
                        <td colspan="2"><label for="comments">Opmerkingen</label></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td><label for="price_intros">Prijs per introducé</label></td>
                        <td><input id="price_intros" type="text" name="price_intros" placeholder="00,00"></td>
                        <td colspan="2" rowspan="2"><textarea id="comments" name="comments" placeholder="Niet verplicht"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td><label for="max_reserves">Max Reserves</label></td>
                        <td><input id="max_reserves" type="number" name="max_reserves" value="0"></td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Opslaan</button>
            <p><a>Annuleren</a></p>
    </div>
</div>
<script>
function bus_yes(){
    document.getElementById("bus_form").className = "table_row";
}
function bus_no(){
    document.getElementById("bus_form").className = "display_none";
}
function bus_save(){
    document.getElementById("bus_form").className = "display_none";
}
function test_check(params) {
    if(conditions){
        return true;
    }
    else 
        return false;    
}
</script>
<script>
$("#max_members").keypress(function(e) {
    var val = $("#max_members").val();
    var lastChar = String.fromCharCode(e.which);
    if (lastChar!=1&&lastChar!=2&&lastChar!=3&&lastChar!=4&&lastChar!=5&&lastChar!=6&&lastChar!=7&&lastChar!=8&&lastChar!=9&&lastChar!=0) {
        e.preventDefault();
    }
});

$("#max_intros").keypress(function(e) {
    var val = $("#max_intros").val();
    var lastChar = String.fromCharCode(e.which);
    if (lastChar!=1&&lastChar!=2&&lastChar!=3&&lastChar!=4&&lastChar!=5&&lastChar!=6&&lastChar!=7&&lastChar!=8&&lastChar!=9&&lastChar!=0) {
        e.preventDefault();
    }
});

$("#price_members").keypress(function(e) {
    var val = $("#price_members").val();
    var lastChar = String.fromCharCode(e.which);
    if (lastChar!=1&&lastChar!=2&&lastChar!=3&&lastChar!=4&&lastChar!=5&&lastChar!=6&&lastChar!=7&&lastChar!=8&&lastChar!=9&&lastChar!=0&&lastChar!=",") {
        e.preventDefault();
    } else if (lastChar==","&&val.indexOf(',')>=0) {
        e.preventDefault();
    };
});

$("#price_members").focusout(function() {
    var val = $("#price_members").val();
    if (val != "") {
        val = val.replace(',','.');
        val = parseFloat(val);
        var result = val.toFixed(2).replace('.',',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
        if (result.indexOf(',')<0) result += ',00';
        while (result.indexOf(',') > result.length-3) result += '0';
        $("#price_members").val(result);
    }
});

$("#price_intros").keypress(function(e) {
    var val = $("#price_intros").val();
    var lastChar = String.fromCharCode(e.which);
    if (lastChar!=1&&lastChar!=2&&lastChar!=3&&lastChar!=4&&lastChar!=5&&lastChar!=6&&lastChar!=7&&lastChar!=8&&lastChar!=9&&lastChar!=0&&lastChar!=",") {
        e.preventDefault();
    } else if (lastChar==","&&val.indexOf(',')>=0) {
        e.preventDefault();
    };
});

$("#price_intros").focusout(function() {
    var val = $("#price_intros").val();
    if (val != "") {
        val = val.replace(',','.');
        val = parseFloat(val);
        var result = val.toFixed(2).replace('.',',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
        if (result.indexOf(',')<0) result += ',00';
        while (result.indexOf(',') > result.length-3) result += '0';
        $("#price_intros").val(result);
    }
});
</script>
@include('layouts.footer')