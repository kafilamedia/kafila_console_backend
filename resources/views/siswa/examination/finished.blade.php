@extends('layouts.master')

@section('content')
	{{-- <h1 class="title">Examination</h1> --}}

	<x-kartu>
		<x-slot name="title">Hasil Ujian</x-slot>
		<h4 class="title is-4">Terima Kasih</h4>
		<hr>
		<p>Anda telah menyelesaikan ujian, kami akan segera mengirimkan hasilnya melalui email, telepon atau pesan singkat.</p>
		<hr>
		<p class="has-text-centered">
			<i class="fas fa-heart"></i>
		</p>
	</x-kartu>
@endsection