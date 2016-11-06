@include('layouts.header')

<div class="container">
    <div class="avatarright">
        <h1>Geen profiel</h1>
        <img src="http://localhost/AllegriaV2/public/avatars/default.jpg">
    </div>
    <div class="box">
        <?php if ($members == "[]") : ?>
            <p style="color:red">Geen gegevens gevonden</p>
        <?php else : ?>
  
        <!-- echo out the system feedback (error and success messages) -->
        <table class="table account single">
            <thead>
                <tr>
                    <td colspan="2">Persoonlijk</td>
                    <td colspan="2">Adres</td>
                    <td colspan="2">School</td>
                </tr>
            </thead>
            <tbody>
            @foreach ($members as $member)
                <tr>
                    <td>Achternaam</td>
                    <td>{{ $member->lastname }}</td>
                    <td>Straat</td>
                    <td>{{ $member->address }}</td>
                    <td>RNRnummer</td>
                    <td>{{ $member->RNRnumber }}</td>
                </tr>
                <tr>
                    <td>Tussenvoegsel</td>
                    <td>{{ $member->insertion }}</td>
                    <td>Huisnummer</td>
                    <td>{{ $member->housenumber }}</td>
                    <td>Gebouw</td>
                    <td>{{ $member->location_building }}</td>
                </tr>
                <tr>
                    <td>Voornaam</td>
                    <td>{{ $member->firstname }}</td>
                    <td>Postcode</td>
                    <td>{{ $member->zipcode }}</td>
                    <td>Lokaal</td>
                    <td>{{ $member->location_floor }}</td>
                </tr>
                <tr>
                    <td>Voorletters</td>
                    <td>{{ $member->initials }}</td>
                    <td>Plaats</td>
                    <td>{{ $member->place }}</td>
                    <td>Lid sinds</td>
                    <td>{{ $member->member_since }}</td>
                </tr>
                <tr>
                    <td>Aanhef</td>
                    <td>{{ $member->salutation }}</td>
                    <td colspan="2"></td>
                    <td>Email</td>
                    <td>{{ Auth::user()->email }}</td>
                </tr>
                <tr>
                    <td>Geboortedatum</td>
                    <td>{{ $member->birthday }}</td>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td>Telefoonnummer</td>
                    <td>{{ $member->phonenumber }}</td>
                    <td colspan="4"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p><a href="<?php echo Config::get('URL'); ?>account/edit" class="allegriabutton">Profiel wijzigen</a> <a href="<?php echo Config::get('URL'); ?>account/editpassword" class="allegriabutton">Wachtwoord wijzigen</a></p>
        <?php endif; ?>
    </div>
</div>


@include('layouts.footer')
