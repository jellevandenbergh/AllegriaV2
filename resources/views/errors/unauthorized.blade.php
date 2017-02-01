@include('layouts.header')
<div class="container">
	@include('layouts.feedback')
	<h1>Jij hebt niet de rechten om deze pagina te bekijken</h1>
	<audio autoplay="true" type="audio/mpeg" src="{{ url('sounds/watbenjeaanhetdoen.mp3') }}"></audio>
	<p><a href="{{ url('') }}">Terug naar Home</a></p>
</div>
@include('layouts.footer')
