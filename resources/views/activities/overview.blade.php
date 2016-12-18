@include('layouts.header')


<div class="container">
    <h1></h1>
    <span class="clear"></span>
    <div class="box">
        @include('layouts.feedback')
            <table class="table activities-signup single">
                <thead>
                    <tr>
                        <td colspan="2">Algemeen</td>
                        <td colspan="2">Deelnemers</td>
                        <td colspan="2">Overige</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($get_activitie as $activitie)
                    <tr>
                        <td>Naam</td>
                        <td>{{ $activitie->name }}</td>
                        <td>Max introducés</td>
                        <td>{{ $activitie->max_intros }}</td>
                        <td>Vrije plekken</td>
                        <td>Freeplace</td>
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
                    @endforeach
                </tbody>
            </table>

            <div class="hr"></div>

            <form method="post" action="{{ url('/overview') }}" class="allegriaform">

                <h3>Bevestiging:</h3>
                @if(empty($get_activitie_signup_confirmed))
                <p>Geen bevestigingen gevonden</p>
                @else
                <table class="table activities-signout single">
                    <thead id="theadconfirmNo">
                        <tr>
                            <td><label><i class="fa fa-check" id="select-all-confirm-no"></i></label></td>
                            <td id="confirmNoFirstname" class="link-no">Voornaam <i class="fa"></i></td>
                            <td id="confirmNolastname" class="link-no">Achternaam <i class="fa"></i></td>
                            <td id="confirmNoEmail" class="link-no">Email <i class="fa"></i></td>
                            <td id="confirmNoQuests" class="link-no">Aantal intro's <i class="fa"></i></td>
                            <td id="confirmNoAmount" class="link-no">Totaal bedrag <i class="fa"></i></td>
                            <td id="confirmNoPaid" class="link-no">Betaald <i class="fa"></i></td>
                            <td id="confirmNoSignup" class="link-no">Datum aanmelding <i class="fa fa-caret-down"></i></td>
                            <td id="confirmNoRemember" class="link-no">Herinnerings email <i class="fa"></i></td>
                        </tr>
                    </thead>
                    @foreach($get_activitie_signup_confirmed as $confirmed)
                    <tbody id="tbodyconfirmNo">
                         <tr>
                            <td colspan="1"></td>
                            <td>{{ $confirmed->firstname }}</td>
                            <td>{{ $confirmed->lastname }}</td>
                            <td>{{ $confirmed->email }}</td>
                            <td>0</td>
                            <td>Total_price</td>
                            <td>{{ $confirmed->paid }}</td>
                            <td>{{ $confirmed->datetime_signup }}</td>
                            <td>{{ $confirmed->remembersent }}</td>
                        </tr>
                    </tbody>
                    @endforeach
                </table>
                @endif
                <h3>Geen bevestiging:</h3>
                @if(empty($get_activitie_signup_not_confirmed))
                <p>Geen niet-bevestigingen gevonden</p>
                @else
                <table class="table activities-signout single">
                    <thead id="theadconfirmNo">
                        <tr>
                            <td><label><i class="fa fa-check" id="select-all-confirm-no"></i></label></td>
                            <td id="confirmNoFirstname" class="link-no">Voornaam <i class="fa"></i></td>
                            <td id="confirmNolastname" class="link-no">Achternaam <i class="fa"></i></td>
                            <td id="confirmNoEmail" class="link-no">Email <i class="fa"></i></td>
                            <td id="confirmNoQuests" class="link-no">Aantal intro's <i class="fa"></i></td>
                            <td id="confirmNoAmount" class="link-no">Totaal bedrag <i class="fa"></i></td>
                            <td id="confirmNoPaid" class="link-no">Betaald <i class="fa"></i></td>
                            <td id="confirmNoSignup" class="link-no">Datum aanmelding <i class="fa fa-caret-down"></i></td>
                            <td id="confirmNoRemember" class="link-no">Herinnerings email <i class="fa"></i></td>
                        </tr>
                    </thead>
                    @foreach($get_activitie_signup_not_confirmed as $not_confirmed)
                    <tbody id="tbodyconfirmNo">
                         <tr>
                            <td colspan="1"></td>
                            <td>{{ $not_confirmed->firstname }}</td>
                            <td>{{ $not_confirmed->lastname }}</td>
                            <td>{{ $not_confirmed->email }}</td>
                            <td>0</td>
                            <td>Total_price</td>
                            <td>{{ $not_confirmed->paid }}</td>
                            <td>{{ $not_confirmed->datetime_signup }}</td>
                            <td>{{ $not_confirmed->remembersent }}</td>
                        </tr>
                    </tbody>
                    @endforeach
                </table>
                @endif
                <ul class="action-menu">
                    <li>
                        <a class="allegriabutton" id="mail">Verstuur mail <i class="fa fa-caret-down"></i></a>
                        <ul class="display-none action-sub" id="action-mail">
                            <!-- <li><input type="submit" name="submit" value="Handmatig"></li> -->
                            <li><input type="submit" name="submit" value="Betaal herinnering"></li>
                            <li><input type="submit" name="submit" value="Bevestiging"></li>
                            <li><input type="submit" name="submit" value="Annulering"></li>
                        </ul>
                    </li>
                    <li>
                        <a class="allegriabutton" id="pay">Betaald <i class="fa fa-caret-down"></i></a>
                        <ul class="display-none action-sub" id="action-pay">
                            <li><input type="submit" name="submit" value="Ja"></li>
                            <li><input type="submit" name="submit" value="Nee"></li>
                        </ul>
                    </li>
                    <li><a href="" target="_blank" class="allegriabutton">Printen <i class="fa fa-print"></i></a></li>

                    <li><a href="" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a></li>
                </ul>
                <input type="hidden" name="id" value="">
            </form>
            <div class="hr"></div>
            <br>
            <p><a href=" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a></p>
    </div>
</div>

<script>
$('#select-all-confirm-yes').click(function(event) {
    if ($('.checkbox-confirm-yes').is(":checked")) {
        $('.checkbox-confirm-yes').each(function() {
            $(this).prop('checked', false);
        })
    } else {
        $('.checkbox-confirm-yes').each(function() {
            $(this).prop('checked', true);
        })
    }
});
$('#select-all-confirm-no').click(function(event) {
    if ($('.checkbox-confirm-no').is(":checked")) {
        $('.checkbox-confirm-no').each(function() {
            $(this).prop('checked', false);
        })
    } else {
        $('.checkbox-confirm-no').each(function() {
            $(this).prop('checked', true);
        })
    }
});

$('#mail').click(function(event){
    $('#action-mail').toggle();
    $('#action-pay').hide();
});
$('#pay').click(function(event){
    $('#action-pay').toggle();
    $('#action-mail').hide();
});

////////////////////////////////////////////////////

var extrarows = 0; // important setting!!
var sortColumn = true; // true = ASC
var column = "signup";
var price_intros = <?= $activitie->price_intros ?>;
var price_members = <?= $activitie->price_members ?>;
var ConfirmNo = [];
var ConfirmYes = [];
var isBusySortingTable = false;



getMembers();
$('.link-yes').on('click', function(event){sortTable(event, 'confirmYes')});
$('.link-no').on('click', function(event){sortTable(event, 'confirmNo')});

function getMembers()
{
    $.ajax({
        url:"",
        success:setMembers
    });
}

function setMembers(respons){
    data = $.parseJSON(respons);
    for (var d in data) {
console.log(d);
        for (var p in data[d]) {
            if (data[d][p] == null)
                data[d][p] = "-";
        };        
        var totalPrice = (data[d]['count'] * price_intros) + price_members;
        data[d]['amount'] = "€"+(totalPrice/100).toFixed(2).toString().replace(".", ",");
        if (data[d]['paid'] == 0) {
            data[d]['paid'] = "Nee";
        } else {
            data[d]['paid'] = "Ja";
        };
        if (data[d]['confirmation'] == 0) {
            ConfirmNo.push(data[d]);
        } else {
            ConfirmYes.push(data[d]);
        };
    };
    buildTable();
}

function buildTable()
{   
    var tableConfirmYes = '';
    for (var i in ConfirmYes) {
        tableConfirmYes += '<tr id="data'+ConfirmYes[i].id+'" class="data link">';
            tableConfirmYes += '<td><input type="checkbox" class="checkbox-confirm-yes" id="checkbox-'+ConfirmYes[i].id+'" name="checkbox-'+ConfirmYes[i].id+'" value="'+ConfirmYes[i].id+'"></td>'
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].id+'">'+ConfirmYes[i].firstname+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].id+'">'+ConfirmYes[i].lastname+' '+ConfirmYes[i].insertion+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].id+'">'+ConfirmYes[i].user_email+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].id+'">'+ConfirmYes[i].count+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].id+'">'+ConfirmYes[i].amount+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].id+'">'+ConfirmYes[i].paid+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].id+'">'+ConfirmYes[i].datetime_signup+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].id+'">'+ConfirmYes[i].remembersent+'</label></td>';
        tableConfirmYes += '</tr>';
    };
    $('#tbodyconfirmYes').html(tableConfirmYes);

    var tableConfirmNo = '';
    for (var i in ConfirmNo) {
        tableConfirmNo += '<tr id="data'+ConfirmNo[i].id+'" class="data link">';
            tableConfirmNo += '<td><input type="checkbox" class="checkbox-confirm-no" id="checkbox-'+ConfirmNo[i].id+'" name="checkbox-'+ConfirmNo[i].id+'" value="'+ConfirmNo[i].id+'"></td>'
            tableConfirmNo += '<td><label for="checkbox-'+ConfirmNo[i].id+'">'+ConfirmNo[i].firstname+'</label></td>';
            tableConfirmNo += '<td><label for="checkbox-'+ConfirmNo[i].id+'">'+ConfirmNo[i].lastname+' '+ConfirmNo[i].insertion+'</label></td>';
            tableConfirmNo += '<td><label for="checkbox-'+ConfirmNo[i].id+'">'+ConfirmNo[i].user_email+'</label></td>';
            tableConfirmNo += '<td><label for="checkbox-'+ConfirmNo[i].id+'">'+ConfirmNo[i].count+'</label></td>';
            tableConfirmNo += '<td><label for="checkbox-'+ConfirmNo[i].id+'">'+ConfirmNo[i].amount+'</label></td>';
            tableConfirmNo += '<td><label for="checkbox-'+ConfirmNo[i].id+'">'+ConfirmNo[i].paid+'</label></td>';
            tableConfirmNo += '<td><label for="checkbox-'+ConfirmNo[i].id+'">'+ConfirmNo[i].datetime_signup+'</label></td>';
            tableConfirmNo += '<td><label for="checkbox-'+ConfirmNo[i].id+'">'+ConfirmNo[i].remembersent+'</label></td>';
        tableConfirmNo += '</tr>';
    };
    $('#tbodyconfirmNo').html(tableConfirmNo);
}

function sortTable(event, confirm)
{
    if (isBusySortingTable) return;
    isBusySortingTable = true;
    $('#thead'+confirm+'>tr td i').removeClass('fa-caret-down').removeClass('fa-caret-up');
    var columnName = event.target.textContent;
    switch(columnName){
        case"Voornaam ":
            if (column == "firstname") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#'+confirm+'Firstname i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "firstname";
        break;
        case"Achternaam ":
            if (column == "lastname") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#'+confirm+'Lastname i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "lastname";
        break;
        case"Email ":
            if (column == "email") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#'+confirm+'Email i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "email";
        break;
        case"Aantal intro's ":
            if (column == "quests") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#'+confirm+'Quests i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "quests";
        break;
        case"Totaal bedrag ":
            if (column == "amount") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#'+confirm+'Amount i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "amount";
        break;
        case"Betaald ":
            if (column == "paid") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#'+confirm+'Paid i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "paid";
        break;
        case"Datum aanmelding ":
            if (column == "datetime_signup") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#'+confirm+'Signup i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "datetime_signup";
        break;
        case"Herinnerings email ":
            if (column == "remembersent") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#'+confirm+'Remember i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "remembersent";
        break;
    }

    function compareMembersDESC(a,b){
            if (a[column] > b[column]) return 1;
            if (a[column] < b[column]) return -1;
            return 0;
    }

    function compareMembersASC(a,b){
            if (a[column] < b[column]) return 1;
            if (a[column] > b[column]) return -1;
            return 0;
    }

    // get tbody
    // var tbody+confirm = document.getElementById('tbody'+confirm);
    var tbody = document.getElementById('tbody');
    
    // extract tr-nodes and link them to the data-array, this detaches all tr-nodes from html-table, 
    // without destroying them.
    var nr = 0;
    var row = 0;
    while (tbodyConfirmYes.firstChild) {
        if (row == 0){
            ConfirmYes[nr].tr = [];
        }
        ConfirmYes[nr].tr[row] = tbodyConfirmYes.removeChild(tbodyConfirmYes.firstChild);
        row++;
        if (row > extrarows){
            row = 0;
            nr++;
        }
    }
    
    // sort the array with compare

    if (sortColumn) {
        ConfirmYes.sort(compareMembersDESC);
    } else {
        ConfirmYes.sort(compareMembersASC);
    }

    
    // attach all tr-nodes to html-table
    for (var d in ConfirmYes){
        for (t in ConfirmYes[d].tr)
            tbodyConfirmYes.appendChild(ConfirmYes[d].tr[t]);
    }
    isBusySortingTable = false;
}
</script>

@include('layouts.header')