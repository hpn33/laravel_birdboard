@extends('layouts.app')

@section('content')

	<div class="flex items-center mb-3">
		<a href="/projects/create">New Project</a>
	</div>

	<div class="flex flex-wrap">

		@forelse($projects as $project)
			<div class="bg-white mr-4 p-5 rounded shadow w-1/4" style="height: 200px">
				<h3 class="font-normal text-xl py-4">{{ $project->title }}</h3>

				<div class="text-gray-text">{{ Illuminate\Support\Str::limit($project->description, 100) }}</div>
			</div>
		@empty
			<div>No Project yet.</div>
		@endforelse
	
	</div>

@endsection