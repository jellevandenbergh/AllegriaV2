@include('layouts.header')
<div class="container">
	@include('layouts.feedback')
	<h1>Verificatielink versturen</h1>
	<form method="post" action="">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<p><a href="http://localhost/AllegriaV2/public">Terug naar Home</a></p>
	<p>Weet je zeker dat je een verificatielink naar <strong>{{ $fullname }}</strong> wilt versturen? Deze link is 24 uur geldig.</p>
	<input type="submit" name="submit" value="Vesturen">
	</form>
</div>
@include('layouts.footer')