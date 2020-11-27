<div class="modal{{ $show ? ' is-active' : '' }}">
  <div class="modal-background"></div>
  <div class="modal-content">
    <div class="box">
    	<h4 class="title is-4">Easy Login URL</h4>

	    @if(! is_null($key))
    		<textarea id="elk-hash" style="background: #eaeaea" cols="30" rows="4" class="textarea">{{ route('easy-login', ['key' => $key]) }}</textarea>

	    	<div class="has-text-right" style="margin-top: 30px;">
	    		<button data-clipboard-target="#elk-hash" class="button is-primary clipboard">
	    			<span class="icon">
	    				<svg class="svg-inline--fa fa-copy fa-w-14" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="copy" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
	    					<path fill="currentColor" d="M320 448v40c0 13.255-10.745 24-24 24H24c-13.255 0-24-10.745-24-24V120c0-13.255 10.745-24 24-24h72v296c0 30.879 25.121 56 56 56h168zm0-344V0H152c-13.255 0-24 10.745-24 24v368c0 13.255 10.745 24 24 24h272c13.255 0 24-10.745 24-24V128H344c-13.2 0-24-10.8-24-24zm120.971-31.029L375.029 7.029A24 24 0 0 0 358.059 0H352v96h96v-6.059a24 24 0 0 0-7.029-16.97z"></path>
	    				</svg>
	    			</span>
	    			<span>Copy Key</span>
	    		</button>
	    	</div>
	    @endif
    </div>
  </div>
  <button wire:click="$set('show', false)" class="modal-close is-large" aria-label="close"></button>
</div>

