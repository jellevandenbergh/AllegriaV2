<table border="1" width="100%">
    <thead>
        <tr>
            <td>Naam</td>
            <td>Geboortedatum</td>
            <td>Email</td>
            <td>Betaald</td>
            <td>Inschrijfdatum</td>
        </tr>
    </thead>
    <tbody>
        @foreach($passengerlist as $passenger)
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

<script>
    window.print();
</script>
