@include('layouts.header')
<div class="container">
    <h1>{{ $fullname }}</h1>
    <div class="box">
    @include('layouts.feedback')
        <div class="avatarleft">
            <p><img src=""></p>
            <p><a href="">Foto bewerken</a></p>
        </div>
        <form method="post" action="" class="allegriaform">
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="">
            <table class="table account single">
                <thead>
                    <tr>
                        <td colspan="2">Persoonlijk</td>
                        <td colspan="2">Adres</td>
                        <td colspan="2">School</td>
                    </tr>
                </thead>
                @foreach($get_member as $member)
                <tbody>
                    <tr>
                        <td>Achternaam</td>
                        <td><input type="text" name="lastname" value="{{ $member->lastname }}" required>
                        <td>Straat</td>
                        <td><input type="text" name="address" value="{{ $member->address }}" required></td>
                        <td>RNRnummer</td>
                        <td><input type="number" name="RNRnumber" value="{{ $member->RNRnumber }}" maxlength="6"></td>
                    </tr>
                    <tr>
                        <td>Tussenvoegsel</td>
                        <td><input type="text" name="insertion" value="{{ $member->insertion }}"></td>
                        <td>Huisnummer</td>
                        <td><input type="text" name="housenumber" value="{{ $member->housenumber }}" maxlength="10"></td>
                        <td>Gebouw</td>
                        <td><input type="text" name="location_building" value="{{ $member->location_building }}"></td>
                    </tr>
                    <tr>
                        <td>Voornaam</td>
                        <td><input type="text" name="firstname" value="{{ $member->firstname }}" required></td>
                        <td>Postcode</td>
                        <td><input type="text" name="zipcode" value="{{ $member->zipcode }}" maxlength="6"></td>
                        <td>Lokaal</td>
                        <td><input type="text" name="location_floor" value="{{ $member->location_floor }}"></td>
                    </tr>
                    <tr>
                        <td>Voorletter</td>
                        <td><input type="text" name="initials" value="{{ $member->initials }}" required></td>
                        <td>Plaats</td>
                        <td><input type="text" name="place" value="{{ $member->place }}"></td>
                        <td>Lid sinds</td>
                        <td><input type="date" name="member_since" value="{{ Helpers::convertDate($member->member_since) }}" required></td>
                    </tr>
                    <tr>
                        <td>Aanhef</td>
                        <td><select name="salutation"><option value="de heer"<?=(($member->salutation=="de heer")?" selected":"")?>>De heer</option><option value="mevrouw"<?=(($member->salutation=="mevrouw")?" selected":"")?>>Mevrouw</option></td>
                        <td colspan="2"></td>
                        <td>Email:</td>
                        <td><input type="email" name="email" value="{{ $member->email }}" required></td>
                    </tr>
                    <tr>
                        <td>Geboortedatum</td>
                        <td><input type="date" name="birthday" value="{{ Helpers::convertDate($member->birthday) }}" required></td>
                        <td colspan="2"></td>
                        <td>Geverifieerd</td>
                        <td><?php echo($member->user_activated < 2 ? 'Nee' : 'Ja') ?></td>
                    </tr>
                    <tr>
                        <td>Telefoonnummer</td>
                        <td><input type="text" name="phonenumber" value="{{ $member->phonenumber }}"></td>
                        <td colspan="3"></td>
@if($member->user_activated == 1)
                        <td><a href="http://localhost/AllegriaV2/public/members/sendverification/{{$member_id}}" class="allegriabutton" type="submit"><i class="fa fa-mail-forward"></i> Stuur verivicatielink</a></td>
@endif
                    </tr>
                    </tbody>
                    @endforeach
                </table>
            <p><input type="submit" name="submit" value="Opslaan"> <a href="">Annuleren</a></p>
        </form>
    </div>
</div>
@include('layouts.footer')
