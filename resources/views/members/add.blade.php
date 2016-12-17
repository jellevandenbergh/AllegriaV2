@include('layouts.header')
<div class="container">
    <h1>Lid toevoegen</h1>
    <div class="box">

        @include('layouts.feedback')

		<form method="post" action="" class="allegriaform">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<table class="table activities-signup single">
				<thead>
					<tr>
	                    <td colspan="2"><b>Persoonlijk</b></td>
	                    <td colspan="2"><b>Adres</b></td>
	                    <td colspan="2"><b>School</b></td>
	                </tr>
				</thead>
	            <tbody>
	                <tr>
	                    <td><label for="lastname">Achternaam</label></td>
	                    <td><input id="lastname" type="text" name="lastname" placeholder="Verplicht" required></td>
	                    <td><label for="address">Straat</label></td>
	                    <td><input id="address" type="text" name="address" placeholder="Verplicht" required></td>
	                    <td><label for="RNRnumber">RNRnummer</label></td>
	                    <td><input id="RNRnumber" type="number" name="RNRnumber" placeholder="123456" maxlength="6" required></td>
	                </tr>
	                <tr>
	                    <td><label for="insertion">Tussenvoegsel</label></td>
	                    <td><input id="insertion" type="text" name="insertion"></td>
	                    <td><label for="housenumber">Huisnummer</label></td>
	                    <td><input id="housenumber" type="text" name="housenumber" placeholder="14 A" required></td>
	                    <td><label for="location_building">Gebouw</label></td>
	                    <td><input id="location_building" type="text" name="location_building" placeholder="Verplicht" required></td>
	                </tr>
	                <tr>
	                    <td><label for="firstname">Voornaam</label></td>
	                    <td><input id="firstname" type="text" name="firstname" placeholder="Verplicht" required></td>
	                    <td><label for="zipcode">Postcode</label></td>
	                    <td><input id="zipcode" type="text" name="zipcode" placeholder="1234 AB" required></td>
	                    <td><label for="location_floor">Lokaal</label></td>
	                    <td><input id="location_floor" type="text" name="location_floor" placeholder="Verplicht" required></td>
	                </tr>
	                <tr>
	                    <td><label for="initials">Voorletter</label></td>
	                    <td><input id="initials" type="text" name="initials" placeholder="Verplicht" required></td>
	                    <td><label for="place">Plaats</label></td>
	                    <td><input id="place" type="text" name="place" placeholder="Verplicht" required></td>
	                    <td><label for="member_since">Lid sinds</label></td>
	                    <td><input id="member_since" type="date" name="member_since" placeholder="dd-mm-jjjj" required></td>
	                </tr>
	                <tr>
	                    <td><label>Aanhef</label></td>
	                    <td><select name="salutation"><option value="de heer">De heer</option><option value="mevrouw">Mevrouw</option></td>
	                    <td colspan="2"></td>
	                    <td><label for="email">Email</label></td>
	                    <td><input id="email" type="email" name="email" placeholder="voorbeeld@gmail.com" required></td>
	                </tr>
	                <tr>
	                    <td><label for="birthday">Geboortedatum</label></td>
	                    <td><input id="birthday" type="date" name="birthday" placeholder="dd-mm-jjjj" required></td>
	                    <td colspan="2"></td>
	                    <td>Functie</td>
@if(Auth::user()->user_account_type < 3)
	                    <td>
	                    <select name="function">
	                    <option value="1">Lid</option>
	                    <option value="2">Bestuur</option>
	                    </select>
	                    </td>
@elseif(Auth::user()->user_account_type > 3)	                   
	                    <td>
	                    <select name="function">
	                    <option value="1">Lid</option>
	                    <option value="2">Bestuur</option>
	                    <option value="3">Admin</option>
	                    <option value="4">Super Admin</option>
	                    </select>
	                    </td>
@endif	                  
	                </tr>
					<tr>
                        <td><label for="phonenumber">Telefoonnummer</label></td>
                        <td><input id="phonenumber" type="text" name="phonenumber" placeholder="06-12345678"></td>
                        <td colspan="4"></td>
                    </tr>
	            </tbody>
	        </table>
			<p><input type="submit" name="submit" value="Toevoegen"> <a href="" class="allegriabutton">Annuleren</a></p>
    	</form>
    </div>
</div>
@include('layouts.footer')