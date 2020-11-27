@extends('layouts.master')

@section('content')
	<h1 class="title">
		<span>Pendaftar</span>
		<span class="tag is-dark">calon siswa</span>
	</h1>

	<div class="level">
		<div class="level-left">
			<a href="{{ route('admin.students.create') }}" class="button is-primary is-outlined">
				<span class="icon">
					<i class="fas fa-plus-circle"></i>
				</span>
				<span>Create Student</span>
			</a>
		</div>
	</div>

	@if($users->count())
		<table class="table is-fullwidth is-vcentered">
			<thead>
				<tr>
					<th>Nama</th>
					<th>Email</th>
					<th class="has-text-centered">Jenjang</th>
					<th class="has-text-centered">Selesai</th>
					<th class="has-text-centered">Nilai</th>
					<th class="has-text-centered">Opsi</th>
					<th class="has-text-centered">Waktu Mulai</th>
					<th class="has-text-centered">Siswa Waktu</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
					@php
                        // $cbt = $cbts->where('jenjang', $user->jenjang)->first();
						$submission = $user->submissions/*->where('cbt_id', $cbt->id)*/->first();
						$isFinished = Arr::get($submission, 'locked');
						$nilai = Arr::get($submission, 'nilai');
					@endphp
					<tr>
						<td>
							<a href="{{ route('admin.students.show', $user) }}">{{ $user->name }}</a>
						</td>
						<td>{{ $user->email }}</td>
						<td class="has-text-centered">{{ Arr::get(['ma' => 'MA', 'mts' => 'MTs'], $user->jenjang) }}</td>
						<td class="has-text-centered has-text-{{ $isFinished ? 'success' : 'warning' }}">
							<i class="fas fa-{{ $isFinished ? 'check' : 'question' }}-circle"></i>
						</td>
						<td class="has-text-centered">{{ $nilai }}</td>
						<td class="has-text-right">
							@livewire('student.keygen', compact('user'))
						</td>
						<td class="has-text-centered">{{ Arr::get($submission, 'start_at') }}</td>
						<td class="has-text-centered">{{ Arr::get($submission, 'sisa_waktu') }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<div class="notification is-warning">No member yet.</div>
	@endif

	@livewire('student.keygen-modal')
@endsection

@section('extra_script')
	<script src="{{ asset('js/clipboard.min.js') }}"></script>
	<script>
		// clipboard
		var clipboard = new ClipboardJS('.clipboard');
	</script>
@endsection
