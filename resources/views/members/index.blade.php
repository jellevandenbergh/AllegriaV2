@include('layouts.header')
<script src="js/jquery.tabledataslider.js"></script>
<style>
.red{
    color: red;
}
</style>
<div class="container">
    <h1>Leden overzicht <i id="waiting" class="fa fa-spinner fa-spin"></i>
        <ul class="action-top">
@if(Auth::user()->user_account_type >= 3)
            <li><a href="members/add" class="allegriabutton"><i class="fa fa-plus"></i> Lid toevoegen</a></li>
@endif
            <!-- <li><a href="<?= Config::get('URL');?>members/sendemail" class="allegriabutton"><i class="fa fa-envelope-o"></i> Email sturen</a></li> -->
            <li class="searchbar"><i class="fa fa-search"></i> <input type="text" class="" name="search" id="search" placeholder="Zoeken op achternaam" required></li>
        </ul>
    </h1>
    <div class="box">

        @include('layouts.feedback')
        <table class="table activities three">
            <thead>
            <tr>
                <td id="sortfirstname" class="link">Voornaam <i class="fa"></i></td>
                <td id="sortlastname" class="link">Achternaam <i class="fa fa-caret-down"></i></td>
                <td id="sortemail" class="link">Email <i class="fa"></i></td>
                <td id="sortlocation" class="link">Locatie <i class="fa"></i></td>
                <td>Bewerken</td>
                <td>Verwijderen</td>
            </tr>
            </thead>
            <tbody id="tbody">
            </tbody>
        </table>

        <div class="hr"></div>
    </div>
</div>
<script>
var base_url = "<?= url(''); ?>";

getMembers();
$("#search").keyup(function(){
    setFilterMembers(this);
});
$('.link').click(sortTable);

var extrarows = 1; // important setting!!
var sortColumn = true; // true = ASC
var column = "lastname";


function getMembers()
{
    $.ajax({
        url: base_url + '/members/getmembers',
        success:setMembers
    });
}

function setMembers(respons){
    data = $.parseJSON(respons);
    for (var d in data) {
        for (var p in data[d]) {
            if (data[d][p] == null)
                data[d][p] = "-";
        };
    };
    buildTable();
}

function buildTable()
{
    $('#waiting').hide();
    var html = '';
    for (var i in data) {
        <?php if (Auth::user()->user_account_type >= 3): ?>
        html += '<tr id="data'+data[i].id+'" class="data link filterIn"><td>'+data[i].firstname+'</td><td>'+data[i].lastname+' '+data[i].insertion+'</td><td id="red">'+data[i].email+'</td><td>'+data[i].location_building+'</td><td class="center"><a href="members/edit/'+data[i].id+'"><i class="fa fa-pencil"></i></a></td><td class="center"><a href="members/delete/'+data[i].id+'"><i class="fa fa-trash-o"></i></a></td></tr>';
<?php else: ?>
        html += '<tr id="data'+data[i].id+'" class="data link filterIn"><td>'+data[i].firstname+'</td><td>'+data[i].lastname+' '+data[i].insertion+'</td><td>'+data[i].email+'</td><td>'+data[i].location_building+'</td></tr>';
<?php endif; ?>
        // extra row's
        html += '<tr class="filterIn detail"><td id="detail'+data[i].id+'" colspan="6"><table class="activities-detail activities-memberdetails">';
            html += '<tr><td colspan="2"><strong>Persoonlijk</strong></td><td colspan="2"><strong>Adres</strong></td><td colspan="2"><strong>School</strong></td></tr>';
            html += '<tr><td>Achternaam</td><td>'+data[i].lastname+'</td><td>Straat</td><td>'+data[i].address+'</td><td>RNRnummer</td><td>'+data[i].RNRnumber+'</td></tr>';
            html += '<tr><td>Tussenvoegsel</td><td>'+data[i].insertion+'</td><td>Huisnummer</td><td>'+data[i].housenumber+'</td><td>Gebouw</td><td>'+data[i].location_building+'</td></tr>';
            html += '<tr><td>Voornaam</td><td>'+data[i].firstname+'</td><td>Postcode</td><td>'+data[i].zipcode+'</td><td>Lokaal</td><td>'+data[i].location_floor+'</td></tr>';
            html += '<tr><td>Voorletters</td><td>'+data[i].initials+'</td><td>Plaats</td><td>'+data[i].place+'</td><td>Lid sinds</td><td>'+data[i].member_since+'</td></tr>';
            html += '<tr><td>Aanhef</td><td>'+data[i].salutation+'</td><td colspan="2"></td><td>Email</td><td>'+data[i].email+'</td></tr>';
            html += '<tr><td>Geboortedatum</td><td>'+data[i].birthday+'</td><td colspan="2"></td><td>Function</td><td>'+data[i].user_account_type+'</td></tr>';
            html += '<tr><td>Telefoonnummer</td><td></td><td colspan="4"></td></tr>';
        html += '</table></tr>';
    };
    document.getElementById('tbody').innerHTML = html;
    $('tr.data').tabledataslider({detailIdPrefix:'detail'});
    $('.activities-memberdetails').css('display','table');
    setBG();
}

isBusySortingTable = false;
function sortTable(event)
{
    if (isBusySortingTable) return;
    isBusySortingTable = true;
    $('#thead>tr td i').removeClass('fa-caret-down').removeClass('fa-caret-up');
    var columnName = event.target.textContent;
    switch(columnName){
        case"Voornaam ":
            if (column == "firstname") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#sortfirstname i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "firstname";
        break;
        case"Achternaam ":
            if (column == "lastname") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#sortlastname i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "lastname";
        break;
        case"Email ":
            if (column == "email") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#sortemail i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "email";
        break;
        case"Locatie ":
            if (column == "location_building") {
                sortColumn = !sortColumn
            } else {
                sortColumn = true;
            }
            $('#sortlocation i').addClass('fa-caret-'+((sortColumn)?'down':'up'));
            column = "location_building";
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
    var tbody = document.getElementById('tbody');

    // extract tr-nodes and link them to the data-array, this detaches all tr-nodes from html-table,
    // without destroying them.
    var nr = 0;
    var row = 0;
    while (tbody.firstChild) {
        if (row == 0){
            data[nr].tr = [];
        }
        data[nr].tr[row] = tbody.removeChild(tbody.firstChild);
        row++;
        if (row > extrarows){
            row = 0;
            nr++;
        }
    }

    // sort the array with compare

    if (sortColumn) {
        data.sort(compareMembersDESC);
    } else {
        data.sort(compareMembersASC);
    }


    // attach all tr-nodes to html-table
    for (var d in data){
        for (t in data[d].tr)
            tbody.appendChild(data[d].tr[t]);
    }
    isBusySortingTable = false;
}

function filterMembers()
{
    var memberin = [];
    var memberout = [];

    for (var d in data) {
        var regexp = new RegExp(filter,"i");
        if (filter == '' || data[d].lastname.search(regexp) >= 0)
            memberin[memberin.length] = data[d].id;
        else
            memberout[memberout.length] = data[d].id;
    }
    /////// hide //////
    for (var o in memberout) {
        $('#data'+memberout[o]).removeClass('filterIn');
        $('#detail'+memberout[o]).parent().removeClass('filterIn');
    }
    ////// show //////
    for (var i in memberin) {
        $('#data'+memberin[i]).addClass('filterIn');
        $('#detail'+memberin[i]).parent().addClass('filterIn');
    }
}

function setFilterMembers(me)
{
    filter = $(me).val();
    filterMembers();
    setBG();
}

function setBG() {
    var flagBG = false;
    $('.data.filterIn').each(function(){
        if (flagBG) {
            $(this).addClass('bg-even');
        } else {
            $(this).removeClass('bg-even');
        }
        flagBG = !flagBG;
    });
}

</script>
@include('layouts.footer')
