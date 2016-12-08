@include('layouts.header')
<div class="container">
    <h1>{{ $fullname }}</h1>
    <div class="box">
        <div class="avatarleft">
            <p><img src=""></p>
            <p><a href="">Foto bewerken</a></p>
        </div>
        <form method="post" action="edit" class="allegriaform">
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
                        <td><input type="text" name="housenumber" value="{{ $member->housenumber }}" maxlength="10"></td>
                        <td>Gebouw</td>
                        <td><input type="text" name="location_building" value="{{ $member->location_building }}"></td>
                    </tr>
                    <tr>
                        <td>Voornaam</td>
                        <td>{{ $member->firstname }}</td>
                        <td>Postcode</td>
                        <td><input type="text" name="zipcode" value="{{ $member->zipcode }}" maxlength="6"></td>
                        <td>Lokaal</td>
                        <td><input type="text" name="location_floor" value="{{ $member->location_floor }}"></td>
                    </tr>
                    <tr>
                        <td>Voorletter</td>
                        <td>{{ $member->initials }}</td>
                        <td>Plaats</td>
                        <td><input type="text" name="place" value="{{ $member->place }}"></td>
                        <td>Lid sinds</td>
                        <td>{{ $member->member_since }}</td>
                    </tr>
                    <tr>
                        <td>Aanhef</td>
                        <td>{{ $member->salutation }}</td>
                        <td colspan="2"></td>
                        <td>Email:</td>
                        <td>{{ $user_email }}</td>
                    </tr>
                    <tr>
                        <td>Geboortedatum</td>
                        <td>{{ $member->birthday }}</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td>Telefoonnummer</td>
                        <td><input type="text" name="phonenumber" value="{{ $member->phonenumber }}"></td>
                        <td colspan="4"></td>
                    </tr>
                    </tbody>
                    @endforeach
                </table>
            <p><input type="submit" name="submit" value="Opslaan"> <a href="">Annuleren</a></p>
        </form>        
    </div>
</div>
@include('layouts.footer')