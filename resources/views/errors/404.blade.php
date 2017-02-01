@include('layouts.header')
<div class="container">
	@include('layouts.feedback')
	<h1>404 Error - Pagina niet gevonden</h1>
	<p><a href="{{ url('') }}">Terug naar Home</a></p>
</div>
@include('layouts.footer')
