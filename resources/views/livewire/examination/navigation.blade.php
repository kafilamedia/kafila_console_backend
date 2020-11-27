<div>
  <div class="level">
  	<div class="level-left">
  		<button wire:click="goPrev" class="button is-primary" {{ (int) $page === 1 ? 'disabled' : '' }}>Sebelumnya</button>
  	</div>
  	<div class="level-item">
  		<button wire:click="updateIfNotSure" class="button is-{{ $notSureStatus ? 'warning' : 'dark is-outlined' }}" {{ ! $isAnswered ? 'disabled' : '' }}>
  			<span class="icon">
  				@if($notSureStatus)
					<svg class="svg-inline--fa fa-check-square fa-w-14" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
						<path fill="currentColor" d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z"></path>
					</svg>
  				@else
					<svg class="svg-inline--fa fa-square fa-w-14" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
						<path fill="currentColor" d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48z"></path>
					</svg>
				@endif
  			</span>
  			<span>Ragu</span>
  		</button>
  	</div>
  	<div class="level-right">
      @if(! $hasNotSureAnswer && $isAnsweredAll && (int) $page === count($pages))
        <button wire:click="finished" class="button is-success is-large">Selesai</button>
      @else
    		<button wire:click="goNext" class="button is-primary" {{ (int) $page === count($pages) ? 'disabled' : '' }}>Berikutnya</button>
      @endif
  	</div>
  </div>
</div>
