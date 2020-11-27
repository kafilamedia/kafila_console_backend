@php

@endphp

<div x-data="handleSoal()">
	@if($question['terkait_soal_sebelumnya'])
		<div class="notification is-info">Soal ini terkait dengan soal sebelumnya.</div>
	@endif
  <div style="padding: 20px;" class="content">
	  <div class="konten-soal">{!! $question['konten'] !!}</div>
	  @if(! is_null(Arr::get($question, 'media.pertanyaan')))
	  	@php
	  		$media_url = Arr::get($question, 'media.pertanyaan.gambar.filename');
	  	@endphp
			<img src="{{ Storage::url('media/' . $media_url) }}">
	  @endif
	</div>

  <div class="area-jawaban">
  	@if($question['jenis'] === 'pg')
			@foreach($question['opsi'] as $key => $pilihan)
				<div style="display: flex;margin: 15px 0;">
					<div style="height: 53px;line-height: 1.8" wire:click="setAnswer('{{ $key }}')" class="option-letter{{ $currentAnswer === $key ? ($notSureStatus ? ' letter-selected-not-sure' : ' letter-selected') : '' }}">{{ $key }}</div>
					<div style="padding: 10px;">
						<div>{!! $pilihan !!}</div>

						@if(! is_null(Arr::get($question, 'media.opsi_' . $key)))
					  	@php
					  		$media_url = Arr::get($question, 'media.opsi_' . $key . '.gambar.filename');
					  	@endphp
							<img src="{{ Storage::url('media/' . $media_url) }}">
					  @endif
					</div>
				</div>
	  	@endforeach
  	@else
  		<div class="field">
  			<div class="control">
  				<textarea wire:dirty.class="is-danger" wire:model.lazy="answerText" cols="30" rows="5" class="textarea" placeholder="Tulis jawaban kamu di sini, dan jangan lupa tekan tombol simpan." x-on:click="answerFocus()"></textarea>
  			</div>
  		</div>
  		<div class="level">
  			<div class="level-left">
  				<p wire:dirty wire:target="answerText" class="has-text-danger">
  					<strong style="margin-right: 5px;">Status:</strong> Jawaban essay belum tersimpan.
  				</p>
  			</div>
  			<div class="level-right">
  				<button x-on:click="answerSaved()" x-bind:disabled="isAnswerNotDirty()" wire:target="answerText" wire:click="setAnswer('{{ $answerText }}')" class="button is-outlined is-primary">Simpan Jawaban Essay</button>
  			</div>
  		</div>
  	@endif
  </div>
</div>
