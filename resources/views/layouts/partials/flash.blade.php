@if(session()->has('flash-message'))
	<div class="Alert Alert--{{ ucwords(session('flash-message-level')) }}">
		{{ session('flash-message') }}
	</div>
@endif
