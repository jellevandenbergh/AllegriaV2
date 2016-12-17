<script src="<?=Config::get('URL')?>js/jquery.tabledataslider.js"></script> 

@include('layouts.header')
<div class="container">
    <h1>Leden overzicht</h1> <!--<i id="waiting" class="fa fa-spinner fa-spin"></i>-->
        <ul class="action-top">
@if(Auth::user()->user_account_type > 3)
            <li><a href="members/add" class="allegriabutton"><i class="fa fa-plus"></i> Lid toevoegen</a></li>
@endif
            <!-- <li><a href="<?= Config::get('URL');?>members/sendemail" class="allegriabutton"><i class="fa fa-envelope-o"></i> Email sturen</a></li> -->
            <li class="searchbar"><i class="fa fa-search"></i> <input type="text" name="search" id="search" placeholder="Zoeken op achternaam" required></li>
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
            <?php foreach($members as $member): ?>
            <tbody id="tbody">
                 <tr>
                    <td>{{ $member->firstname }}</td>
                    <td>{{ $member->lastname }}</td>
                    <td><?php echo($member->user_activated < 2 ? '<strong style="color:red">'.$member->email.'</strong>' : ''.$member->email.'') ?></td>
                    <td>{{ $member->location_building }}</td>
                    <td class="center"><a href="members/edit/{{$member->id}}"><i class="fa fa-pencil"></i></a></td>
                    <td class="center"><a href="members/delete/{{$member->id}}"><i class="fa fa-trash-o"></i></a></td>
                </tr>
            </tbody>
        <?php endforeach; ?>
        </table>

        <div class="hr"></div>
    </div>
</div>
@include('layouts.footer')
