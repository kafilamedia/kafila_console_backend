@extends('layouts.master')

@php
	$submisis =  $cbt->submisi;
@endphp

@section('content')
	<h1 class="title">CBT <span class="tag is-dark is-uppercase">Results</span></h1>

    <div class="level">
        <div class="level-left">
            <div class="field is-grouped">
                <div class="control">
                    <div class="tags has-addons">
                        <span class="tag is-medium is-dark">Jenis</span>
                        <span class="tag is-medium is-info">Mapel</span>
                    </div>
                </div>
                <div class="control">
                    <div class="tags has-addons">
                        <span class="tag is-medium is-dark">Tanggal</span>
                        <span class="tag is-medium is-info">{{ $cbt->tanggal_pelaksanaan->format('d F Y') }}</span>
                    </div>
                </div>

                <div class="control">
                    <div class="tags has-addons">
                        <span class="tag is-medium is-dark">Kelas</span>
                        <span class="tag is-medium is-info">KELAS</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="level-right">
            @if(! $cbt->submisi->where('score_locked', false)->count())
                <form action="{{ route('admin.cbt.scores.download', $cbt) }}" method="POST">
                    @csrf
                    <button class="button is-primary">
                        <span class="icon">
                            <i class="fas fa-download"></i>
                        </span>
                        <span>Download Nilai (Excel)</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

	<table class="table is-fullwidth is-vcentered">
    	<thead>
    		<tr>
    			<th>#</th>
    			<th>Nama Siswa</th>
    			<th class="has-text-centered">Jawaban Benar</th>
                <th class="has-text-centered">Nilai</th>
                @if(count($cbt->soal->where('jenis', 'es')))
                    <th class="has-text-centered">Check</th>
                @endif
    			<th></th>
    		</tr>
    	</thead>
    	<tbody>
    		@foreach($submisis as $submisi)
				<tr>
					<td>{{ $loop->iteration }}</td>
					 <td>{{$submisi->user->name}}</td> {{--{{ Arr::get($students, $submisi->siswa_id) }} --}}
					<td class="has-text-centered">{{ count(Arr::get($submisi, 'nomor_urut_jawaban_benar', [])) }}</td>
					<td class="has-text-centered">{{ $submisi->nilai }}</td>
                    @if(count($cbt->soal->where('jenis', 'es')))
                        @php
                            $nilai_essay = Arr::get($submisi, 'nilai_essay', []);
                        @endphp
                        <td class="has-text-centered">
                            {{-- @if(! $cbt->soal->where('jenis', 'es')->pluck('urut')->diff(array_keys($nilai_essay))->count()) --}}
                            @if($submisi->score_locked)
                                <span class="icon has-text-success">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @else
                                <span class="icon has-text-warning">
                                    <i class="fas fa-question-circle"></i>
                                </span>
                            @endif
                        </td>
                    @endif
					<td class="has-text-right">
						<a href="{{ route('admin.cbt.results.show', [$cbt, $submisi->id]) }}" class="button is-outlined is-primary">Detail</a>
					</td>
				</tr>
    		@endforeach
    	</tbody>
    </table>
@endsection