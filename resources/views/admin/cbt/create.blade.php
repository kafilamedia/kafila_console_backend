@extends('layouts.master')

@section('content')
	<h1 class="title">CBT</h1>

	<x-card title="Create CBT">
		{!! $form !!}
	</x-card> 
@endsection