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
                        <td>{{ $activitie->free_places }}</td>
                    </tr>
                    <tr>
                        <td>Datum activiteit</td>
                        <td>{{ Helpers::convertDate($activitie->date) }}</td>
                        <td>Prijs per introducé</td>
                        <td>{{Helpers::convertPrice($activitie->price_intros) }}</td>
                        <td colspan="2"><?= (($activitie->comments)?"Opmerkingen":"Geen opmerkingen")?></td>
                    </tr>
                    <tr>
                        <td>Uiterste inschrijfdatum</td>
                        <td>{{ Helpers::convertDate($activitie->max_signup_date) }}</td>
                        <td>Prijs per lid</td>
                        <td>{{ Helpers::convertPrice($activitie->price_members) }}</td>
                        <td colspan="2">{{ $activitie->comments }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="hr"></div>
            @if($get_overview_members == '[]')
                <p style="color:red;">Geen inschrijvingen gevonden</p>
            @else
            <form method="post" action="" class="allegriaform">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <h3>Aanmeldingen</h3>
                <table class="table activities-signout single">
                    <thead id="theadconfirmNo">
                        <tr>
                            <td><label><i class="fa fa-check" id="select-all-confirm"></i></label></td>
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
                    <tbody id="tbodyconfirm">
                    </tbody>
                </table>
                <ul class="action-menu">
                    <li>
                        <a class="allegriabutton" id="mail">Verstuur mail <i class="fa fa-caret-down"></i></a>
                        <ul class="display-none action-sub" id="action-mail">
                            <!-- <li><input type="submit" name="submit" value="Handmatig"></li> -->
                            <li><input type="submit" name="submit" value="Betaal herinnering"></li>
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
                    <li><a href="<?= url('activities/passengerlist') . '/' . $activity_id; ?>" target="_blank" class="allegriabutton">Printen <i class="fa fa-print"></i></a></li>

                    <li><a href="" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a></li>
                </ul>
                <input type="hidden" name="id" value="">
            </form>
            @endif
            <br>
            @if($get_overview_reserves == '[]')
                <p style="color:red;">Geen Reservelijst inschrijvingen gevonden</p>
            @else
            <form method="post" action="" class="allegriaform">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <h3>Reservelijst</h3>
                <table class="table activities-signout single">
                    <thead id="theadconfirmNo">
                        <tr>
                            <td><label><i class="fa fa-check" id="select-all-confirm"></i></label></td>
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
                    <tbody id="tbodyreserve">
                        @foreach($get_overview_reserves as $reserve)
                            <tr id="data86" class="data link">
                            <td>
                            <input type="checkbox" class="checkbox-confirm" id="checkbox-{{$reserve->signup_id}}" name="checkbox-{{$reserve->signup_id}}" value="{{$reserve->signup_id}}"></td><td>
                            <label for="checkbox-{{$reserve->signup_id}}">{{$reserve->firstname}}</label>
                            </td>
                            <td>
                            <label for="checkbox-{{$reserve->signup_id}}">{{$reserve->lastname}} {{$reserve->insertion}}</label>
                            </td>
                            <td>
                            <label for="checkbox-{{$reserve->signup_id}}">{{$reserve->email}}</label>
                            </td>
                            <td>
                            <label for="checkbox-{{$reserve->signup_id}}">{{$reserve->singup_intros}}</label>
                            </td>
                            <td>
                            <label for="checkbox-{{$reserve->signup_id}}">{{$reserve->amount}}</label>
                            </td>
                            <td>
                            <label for="checkbox-{{$reserve->signup_id}}">{{$reserve->paid}}</label>
                            </td>
                            <td>
                            <label for="checkbox-{{$reserve->signup_id}}">{{ Helpers::convertDate($reserve->datetime_signup, 'Y-m-d H:i:s') }}</label>
                            </td>
                            <td>
                            <label for="checkbox-{{$reserve->signup_id}}">{{$reserve->remembersent}}</label>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <ul class="action-menu">
                    <li>
                        <a class="allegriabutton" id="replace">Opties<i class="fa fa-caret-down"></i></a>
                        <ul class="display-none action-sub" id="action-replace">
                            <!-- <li><input type="submit" name="submit" value="Handmatig"></li> -->
                            <li><input type="submit" name="submit" value="overzetten naar inschrijvingen"></li>
                            <li><input type="submit" name="submit" value="Annulering"></li>
                        </ul>
                    </li>

                    <li><a href="" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a></li>
                </ul>
                <input type="hidden" name="id" value="">
            </form>
            @endif
            <div class="hr"></div>

            <br>
            <p><a href=" class="allegriabutton"><i class="fa fa-arrow-left"></i> Terug</a></p>
    </div>
</div>

<script>
$('#select-all-confirm').click(function(event) {
    if ($('.checkbox-confirm').is(":checked")) {
        $('.checkbox-confirm').each(function() {
            $(this).prop('checked', false);
        })
    } else {
        $('.checkbox-confirm').each(function() {
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
$('#replace').click(function(event){
    $('#action-replace').toggle();
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

var base_url = "<?=url('');?>";

getMembers();
$('.link-yes').on('click', function(event){sortTable(event, 'confirmYes')});
$('.link-no').on('click', function(event){sortTable(event, 'confirmNo')});


function getMembers()
{
    $.ajax({
        url: base_url + "/activities/overviewmembers/<?=$activity_id?>",
        success:setMembers
    });
}

function setMembers(respons){
    data = $.parseJSON(respons);
    console.log(data);
    if(data == []){
        return false;
    }
    else{
        for (var d in data) {
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
}


function buildTable()
{
    var tableConfirmYes = '';
    for (var i in ConfirmYes) {
        tableConfirmYes += '<tr id="data'+ConfirmYes[i].signup_id+'" class="data link">';
            tableConfirmYes += '<td><input type="checkbox" class="checkbox-confirm" id="checkbox-'+ConfirmYes[i].signup_id+'" name="checkbox-'+ConfirmYes[i].signup_id+'" value="'+ConfirmYes[i].signup_id+'"></td>'
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].signup_id+'">'+ConfirmYes[i].firstname+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].signup_id+'">'+ConfirmYes[i].lastname+' '+ConfirmYes[i].insertion+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].signup_id+'">'+ConfirmYes[i].email+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].signup_id+'">'+ConfirmYes[i].singup_intros+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].signup_id+'">'+ConfirmYes[i].amount+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].signup_id+'">'+ConfirmYes[i].paid+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].signup_id+'">'+ConfirmYes[i].datetime_signup+'</label></td>';
            tableConfirmYes += '<td><label for="checkbox-'+ConfirmYes[i].signup_id+'">'+ConfirmYes[i].remembersent+'</label></td>';
        tableConfirmYes += '</tr>';
    };
    $('#tbodyconfirm').html(tableConfirmYes);
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
    while (tbodyconfirm.firstChild) {
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

@include('layouts.footer')
