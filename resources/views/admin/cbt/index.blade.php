@extends('layouts.master')

@section('content')
	<h1 class="title">CBT</h1>

	<div class="level">
		<div class="level-left">
            <a href="{{ route('admin.cbt.create') }}" class="button is-primary is-rounded">
                <span class="icon">
                    <i class="fas fa-plus"></i>
                </span>
                <span>Create CBT</span>
            </a>
		</div>
        <div class="level-right">
            @if(! request()->has('archived'))
                <a href="{{ route('admin.cbt.index', ['archived' => 1]) }}" class="button is-dark">
                    <span class="icon">
                        <i class="fas fa-archive"></i>
                    </span>
                    <span>Show CBT Archives</span>
                </a>
            @endif
		</div>
	</div>

	@if($cbts->count())
        @if(request()->has('archived'))
            <div class="notification is-info">You are viewing only archived CBTs</div>
	    @endif

		<table class="table is-fullwidth is-vcentered">
			<thead>
				<tr>
                    <th>Jenjang</th>
                    <th>Tanggal</th>
                    <th class="has-text-centered">Soal</th>
                    <th class="has-text-centered">
                        <i class="fas fa-rocket"></i>
                    </th>
                    <th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($cbts as $cbt)
					<tr>
    					<td>{{ Str::upper(Arr::get($cbt, 'jenis')) }}</td>
						<td>{{ $cbt->tanggal_pelaksanaan->format('d F Y') }}</td>
						<td class="has-text-centered">{{ $cbt->jumlah_soal }}</td>
						<td class="has-text-centered">
							<i class="fas fa-{{ $cbt->published ? 'check-circle has-text-success' : 'question-circle has-text-warning' }}"></i>
						</td>
						<td class="has-text-right">
							@if($cbt->submisi_count == 0)
								<a href="{{ route('admin.cbt.edit', $cbt) }}" class="button is-text has-text-primary">
									<i class="fas fa-edit"></i>
								</a>
							@endif

							@if(! $cbt->published)
                                @if($cbt->soal->count() == $cbt->jumlah_soal)
                                    @if($cbt->soal->where('jenis', 'es')->count())
                                        <a href="{{ route('admin.cbt.bobot.create', $cbt) }}" class="button is-text has-text-dark">
                                            <i class="fas fa-rocket"></i>
                                        </a>
                                    @else
                                        <form action="{{ route('admin.cbt.publish.store', $cbt) }}" method="POST" style="display: inline;">
                                            {{ csrf_field() }}
                                            <button class="button is-text has-text-dark" title="Publish" alt="Publish">
                                                <i class="fas fa-rocket"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif

								<form action="{{ route('admin.cbt.destroy', $cbt) }}" method="POST" style="display: inline;">
		                            @csrf
		                            @method('DELETE')
		                            <button name="delete-item" class="button is-text has-text-danger">
		                                <span class="icon">
		                                    <i class="fas fa-trash"></i>
		                                </span>
		                            </button>
		                        </form>
		                    @endif

                            {{--
		                    <a href="{{ route('admin.cbt.clone.create', $cbt) }}" class="button is-dark is-outlined is-rounded">
								<span class="icon">
									<i class="fas fa-copy"></i>
								</span>
								<span>Clone</span>
							</a>
							--}}

                            <form action="{{ route('admin.cbt.archive.store', $cbt) }}" method="POST" style="display: inline;">
                                {{ csrf_field() }}
                                <button class="button is-text has-text-dark" title="Archive" alt="Archive">
                                    <i class="fas fa-archive"></i>
                                </button>
                            </form>


		                    <a href="{{ route('admin.cbt.soal.index', $cbt) }}" class="button is-text has-text-dark">
								<i class="fas fa-list"></i>
							</a>
							<a href="{{ route('admin.cbt.results.index', $cbt) }}" class="button is-text has-text-info">
								<i class="fas fa-play"></i>
							</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

        {{--
		@section('modal')
	        @modal(['modal_id' => 'delete-confirmation', 'close_btn' => true])
	            @include('partials.delete-confirmation')
	        @endmodal
	    @endsection
	    --}}

        <x-modal id="delete-confirmation" close="true">
            @include('partials.delete-confirmation')
        </x-modal>

	@endif
@endsection
