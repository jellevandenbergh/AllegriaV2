@include('layouts.header')
<script src="js/jquery.tabledataslider.js"></script>
<script>
    $(function(){
        $('tr.dataA').tabledataslider({detailIdPrefix:'detailA'});
        $('tr.dataB').tabledataslider({detailIdPrefix:'detailB'});
        $('.activities-memberdetails').css('display','table');
    });
</script>
<div class="container">
    @if(Session::has('feedback_success'))
        <div class="feedback success"><p>{{ Session::get('feedback_success') }}</p></div>
    @endif
    @if(Session::has('feedback_error'))
        <div class="feedback error"><p>{{ Session::get('feedback_error') }}</p></div>
    @endif
    <h1>
        Activiteiten overzicht<?php if (Auth::user()->user_account_type >= 3):?>
        <ul class="action-top">
            <li><a href="<?= Config::get('URL');?>activities/add" class="allegriabutton"><i class="fa fa-plus"></i> Activiteit toevoegen</a><?php endif; ?></li>
        </ul>
    </h1>
    <?php if ($active_activities == "[]"): ?>
        <strong style="color:red;">activiteiten</strong>
    <?php else: ?>
        <div class="box">
            <h3>Aanmelden voor:</h3>
            <table class="table activities dubble">
                <thead>
                    <tr>
                        <td>Naam</td>
                        <td>Datum activiteit</td>
                        <td>Uiterste aanmelddatum</td>
                        <td>Beschikbare plekken</td>
                        <td>Aanmelden</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($active_activities as $activeactivitie): ?>
                    <tr id="dataA{{ $activeactivitie->id }}" class="dataA link">
                        <td>{{ $activeactivitie->name }}</td>
                        <td>{{ $activeactivitie->date }}</td>
                        <td>{{ $activeactivitie->max_signup_date }}</td>
                        <td>Beschikbare plekken</td>
                        <td class="center"><a href="<?= Config::get('URL') . 'activities/signup/' . $activeactivitie->id; ?>"><i class="fa fa-sign-in"></i></a></td>
                    </tr>
                    <tr>
                        <td colspan="6" id="detailA{{ $activeactivitie->id }}">
                            <table class="activities-detail activities-memberdetails">
                                <tr>
                                    <td>Max introducés</td>
                                    <td>{{ $activeactivitie->max_intros }}</td>
                                    <td>Prijs per lid</td>
                                    <td>{{ $activeactivitie->price_members }}</td>
                                    <td colspan="2"><?= (($activeactivitie->comments)?"Opmerkingen":"Geen opmerkingen")?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td>Prijs per introducé</td>
                                    <td>{{ $activeactivitie->price_intros }}</td>
                                    <td colspan="2" rowspan="2">{{ $activeactivitie->comments }}</td>
                                </tr>
                        <?php if ($activeactivitie->comments): ?> 
                                <tr>
                                    <td colspan="6" rowspan="2"></td>
                                </tr>
                        <?php endif; ?>
                            </table>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php if ($signed_up_activities == '[]'): ?>
            <strong style="color:red;">Geen activiteiten waarvoor aangemeld</strong>
        <?php else: ?> 
            <h3>Aangemeld voor:</h3>
            <table class="table activities-all dubble">
                <thead>
                <tr>
                    <td>Naam</td>
                    <td>Datum activiteit</td>
                    <td>Beschikbare plekken</td>
                    <td>Introducés</td>
                    <td>Afmelden</td>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($signed_up_activities as $signed_up_activitie): ?>
                    <tr id="dataA{{ $signed_up_activitie->id }}" class="dataA link">
                        <td>{{ $signed_up_activitie->name }}</td>
                        <td>{{ $signed_up_activitie->date }}</td>
                        <td>{{ $signed_up_activitie->free_places }}</td>
                        <td class="center"><a href="<?= Config::get('URL') . 'activities/quest/' . $signed_up_activitie->id; ?>"><i class="fa fa-plus-square"></i></i></a></td>
                        <td class="center"><a href="<?= Config::get('URL') . 'activities/signout/' . $signed_up_activitie->id; ?>"><i class="fa fa-sign-out"></i></a></td>
                    </tr>
                    <tr>
                        <td colspan="6" id="detailA{{ $signed_up_activitie->id }}">
                            <table class="activities-detail activities-memberdetails">
                                <tr>
                                    <td>Uiterste inschrijfdatum</td>
                                    <td>{{ $signed_up_activitie->max_signup_date }}</td>
                                    <td>Prijs per lid</td>
                                    <td>{{ $signed_up_activitie->price_members }}</td>
                                    <td colspan="2"><?= (($signed_up_activitie->comments)?"Opmerkingen":"Geen opmerkingen")?></td>
                                </tr>
                                <tr>
                                    <td>Max introducés</td>
                                    <td>{{ $signed_up_activitie->max_intros }}</td>
                                    <td>Prijs per introducé</td>
                                    <td>{{ $signed_up_activitie->price_intros }}</td>
                                    <td colspan="2" rowspan="2">{{ $signed_up_activitie->comments }}</td>
                                </tr>
                                <?php if ($signed_up_activitie->comments): ?> 
                                <tr>
                                    <td colspan="6" rowspan="2"></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (Auth::user()->user_account_type >= 2): ?>
            <h3>Alle activiteiten (bestuur):</h3>
            <?php if ($all_activities == '[]'): ?>
                <strong style="color:red;">Geen activiteiten gevonden</strong>
            <?php else: ?>    
                <table class="table activities-all dubble">
                    <thead>
                    <tr>
                        <td>Naam</td>
                        <td>Datum activiteit</td>
                        <td>Actief</td>
            <?php if (Auth::user()->user_account_type >= 2): ?>
                        <td>Overzicht</td>
            <?php if (Auth::user()->user_account_type >= 3): ?>
                        <!-- <td>Bewerken</td> -->
                        <td>Verwijderen</td>
            <?php endif; endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_activities as $all_activitie): ?>
                        <tr id="dataB{{ $all_activitie->id }}" class="dataB link">
                            <td>{{ $all_activitie->name }}</td>
                            <td>{{ $all_activitie->date }}</td>
                            <td><?= ($all_activitie->status == 1?"Nee":"Ja")?></td>
<?php if (Auth::user()->user_account_type >= 2): ?>
                            <td class="center"><a href="<?= Config::get('URL') . 'activities/overview/' . $all_activitie->id; ?>"><i class="fa fa-link"></i></a></td>
<?php if (Auth::user()->user_account_type >= 3): ?>
                            <!-- <td class="center"><a href="<?= Config::get('URL') . 'activities/edit/' . $all_activitie->id; ?>"><i class="fa fa-pencil"></i></a></td> -->
                            <td class="center"><a href="<?= Config::get('URL') . 'activities/delete/' . $all_activitie->id; ?>"><i class="fa fa-trash-o"></i></a></td>
<?php endif; endif; ?>
                        </tr>
                        <tr>
                            <td colspan="6" id="detailB{{ $all_activitie->id }}">
                            <table class="activities-detail activities-memberdetails">
                                <tr>
                                    <td>Uiterste inschrijfdatum</td>
                                    <td>{{ $all_activitie->max_signup_date }}</td>
                                    <td>Prijs per lid</td>
                                    <td>{{ $all_activitie->price_members }}</td>
                                    <td colspan="2"><?= (($all_activitie->comments)?"Opmerkingen":"Geen opmerkingen")?></td>
                                </tr>
                                <tr>
                                    <td>Max introducés</td>
                                    <td>{{ $all_activitie->max_intros }}</td>
                                    <td>Prijs per introducé</td>
                                    <td>{{ $all_activitie->price_intros }}</td>
                                    <td colspan="2" rowspan="2"><?=htmlentities($all_activitie->comments)?></td>
                                </tr>
                                <tr>
                                    <td>Beschikbare plekken</td>
                                    <td>{{ $all_activitie->free_places }}</td>
                                    <td colspan="4" rowspan="2"></td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
@include('layouts.footer')
