@extends('layouts.master')

@section('content')
	<h1 class="title">
		<span>Pendaftar</span>
		<span class="tag is-dark">calon siswa</span>
	</h1>

	<div class="level">
		<div class="level-left">
			<a href="{{ route('admin.students.index') }}" class="button is-primary is-outlined">
				<span class="icon">
					<i class="fas fa-list"></i>
				</span>
				<span>Indeks Student</span>
			</a>
		</div>
	</div>

	<x-kartu>
    <x-slot name="title">Create New Student</x-slot>

    {!! $form !!}
	</x-kartu>
@endsection