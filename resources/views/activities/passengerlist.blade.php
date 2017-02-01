<h1>LEDEN</h1>
<table border="1" width="100%">
    <thead>
        <tr>
            <td><strong>Naam</strong></td>
            <td><strong>Geboortedatum</strong></td>
            <td><strong>Email</strong></td>
            <td><strong>Betaald</strong></td>
            <td><strong>Inschrijfdatum</strong></td>
        </tr>
    </thead>
    <tbody>
        @foreach($passengerlist['lid'] as $passenger)
        <tr>
            <td>{{ Helpers::convertFullname($passenger->firstname, $passenger->lastname, $passenger->insertion) }}</td>
            <td>{{ Helpers::convertDate($passenger->birthday) }}</td>
            <td>{{ $passenger->email }}</td>
            <td>{{ $passenger->paid }}</td>
            <td>{{ Helpers::convertDate($passenger->datetime_signup, 'Y-m-d H:i:s') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<h1>INTRO'S</h1>
<table border="1" width="100%">
    <thead>
        <tr>
            <td><strong>Naam</strong></td>
            <td><strong>Geboortedatum</strong></td>
            <td><strong>Aangemeld door</strong></td>
        </tr>
    </thead>
    <tbody>
        @foreach($passengerlist['intros'] as $intros)
        <tr>
            <td>{{ $intros->name }}</td>
            <td>{{ $intros->birthday }}</td>
            <td>{{ Helpers::convertFullname($intros->firstname, $intros->lastname, $intros->insertion) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    window.print();
</script>
