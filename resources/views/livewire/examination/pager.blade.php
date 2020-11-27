{{-- WE ARE USING ALPINE.JS FOR TOGGLING PAGES NUMBER --}}
<div x-data="handlePager()" style="margin-bottom: 30px;">
	<div class="level">
		<div class="level-left">
			<div class="tags has-addons">
				<span class="tag is-medium is-dark is-size-5">Sisa Waktu</span>
				<span id="timer" class="tag is-medium is-primary is-size-5 has-text-weight-bold">00:00:00</span>
			</div>
		</div>
		<div class="level-right">
			<div class="field is-grouped">
				<div class="control">
					<div class="tags has-addons">
						<span class="tag is-medium is-dark is-size-5">#</span>
						<span class="tag is-medium is-primary is-size-5 has-text-weight-bold">{{ $page }}</span>
					</div>
				</div>
				<div class="control">
					<button x-on:click="open()" class="button is-dark" x-bind:class="{ 'is-danger' : isOpen() }">
						<span class="icon">
							<svg x-show="! isOpen()" class="svg-inline--fa fa-list fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
								<path fill="currentColor" d="M80 368H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16zm0-320H16A16 16 0 0 0 0 64v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16V64a16 16 0 0 0-16-16zm0 160H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16zm416 176H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z"></path>
							</svg>
							<svg x-show="isOpen()" class="svg-inline--fa fa-times fa-w-11" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" data-fa-i2svg="">
								<path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
							</svg>
						</span>
						<span>Peta Soal</span>
					</button>
				</div>
			</div>
		</div>
	</div>
	<div style="right: 0;background: #ccc;border-radius: 0;position: absolute;z-index: 1" class="box clearfix" x-show="isOpen()" x-on:click.away="close()">
		@foreach($pages as $number)
			@php
				$jawaban = Arr::get($jawabanArr, $number);
				$isNotSure = in_array($number, $jawabanRagus);

				if (strlen($jawaban) > 1) {
					$jawaban = 'es';
				}
			@endphp
			<div class="pager-number-container" style="float: left;">
				@if(empty($jawaban))
					<div class="pager-number-answer">{{ $jawaban }}</div>
				@else
					@if($isNotSure)
						<div class="pager-number-answer not-empty-ragu">{{ $jawaban }}</div>
					@else
						<div class="pager-number-answer not-empty">{{ $jawaban }}</div>
					@endif
				@endif
				<button x-on:click="close()" wire:click="goToPage({{$number}})" class="pager-number-item" style="{{ $number === (int) $page ? 'background: #000;color:#fff;' : '' }}">{{ $number }}</button>
			</div>
		@endforeach
	</div>
</div>
