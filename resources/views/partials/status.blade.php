@if(session('status'))
	<div id="status_notification" class="notification is-warning animated fadeInDownBig">
		<button class="delete"></button>
		<p>{{ session('status') }}</p>
	</div>

	@section('status_script')
		<script>
    	var statusEl = document.querySelector('#status_notification');
      // fade out status notification
      /*setTimeout(function () {
          statusEl.classList.replace('fadeInDownBig', 'fadeOutRightBig');
      }, 3000);*/
      var closeStatusBtn = document.querySelector('#status_notification > button.delete');
      closeStatusBtn.addEventListener('click', function () {
          statusEl.classList.replace('fadeInDownBig', 'fadeOutRightBig');
      });
    </script>
	@endsection
@endif