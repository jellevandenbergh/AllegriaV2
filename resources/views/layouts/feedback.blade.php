@if(Session::has('feedback_success'))
    <div class="feedback success"><p>{{ Session::get('feedback_success') }}</p></div>
@endif
@if(Session::has('feedback_error'))
    <div class="feedback error"><p>{{ Session::get('feedback_error') }}</p></div>
@endif