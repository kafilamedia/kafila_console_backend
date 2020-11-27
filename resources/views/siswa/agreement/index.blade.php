@extends('layouts.master')

@section('content')
	<h1 class="title">Syarat dan Ketentuan</h1>

	<x-kartu>
		<x-slot name="title">Butir-butir Syaran dan Ketentuan</x-slot>

		<div class="content">
			<ul>
				<li>Ujian online yang akan anda laksanakan ini bersifat rahasia, peserta wajib menjaga kerahasiannya.</li>
				<li>Hasil ujian ini menentukan diterima atau tidaknya peserta.</li>
				<li>Jenis soal yang akan diujikan adalah pilihan ganda.</li>
				<li>Jumlah soal yang akan diujikan adalah 50 butir.</li>
				<li>Peserta wajib menjawab semua soal dengan jujur, tanpa orang lain, alat atau sumber belajar buku, web online atau aplikasi lainnya.</li>
				<li>Jika ditemukan ketidaksesuaian pada pelakasanaan ujian dengan melanggar ketentuan di atas, peserta akan gugur dalam seleksi PSB.</li>
				<li>Panduan singkat cara menjawab ujian online bisa dilihat di <a href="#"><strong>Laman Panduan</strong></a></li>
			</ul>
		</div>
		<hr>
		<div class="has-text-right">
			<a href="{{ route('siswa.examination.index') }}" class="button is-primary">Saya Setuju</a>
		</div>
	</x-kartu>
@endsection