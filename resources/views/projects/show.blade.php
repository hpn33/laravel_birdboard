@extends('layouts.app')

@section('content')

	<header class="flex items-center mb-3 py-4">
		<div class="flex justify-between items-end w-full">
				
			<p class="text-gray text-sm font-normal">
				<a href="/projects" class="text-gray text-sm font-normal no-underline">My Projects</a> / {{ $project->title }}
			</p>

			<a href="/projects/create" class="button">New Project</a>	
		</div>
		
	</header>

	<main>
		<div class="lg:flex -mx-3">
			<div class="lg:w-3/4 px-3 mb-6">
				<div class="mb-8">
					<h2 class="text-gray font-normal text-lg mb-3">Tasks</h2>
					<!-- tasks -->

					@foreach($project->tasks as $task)
						<div class="card mb-3">
							<form action="{{ $task->path() }}" method="POST">
								@method('PATCH')
								@csrf
								
								<div class="flex">
									<input type="text" name="body" value="{{ $task->body }}" class="w-full {{ $task->completed ? 'text-gray-500' : '' }}" >
									<input type="checkbox" name="completed" onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
								</div>
							</form>
						</div>
					@endforeach

					<div class="card mb-3">
						<form action="{{ $project->path('tasks') }}" method="POST">
							@csrf

							<input placeholder="Add a new Task..." class="w-full" name="body">
						</form>
					</div>
				</div>

				<div>
					<h2 class="text-gray font-normal text-lg mb-3">General Notes</h2>
					<!-- general notes -->
					<form method="POST" action="{{ $project->path() }}">
						@csrf
						@method('PATCH')
						<textarea 
							name="notes" 
							class="card w-full mb-4" 
							style="min-width: 200px" 
							placeholder="Anything special that you want to make a note of?">
							{{ $project->notes }}
						</textarea>
						<button type="submit" class="button">Save</button>
					</form>
				</div>
			</div>

			<div class="lg:w-1/4 px-3">
				@include('projects.card')
				<!-- div class="card">					
					<h1>{{ $project->title }}</h1>

					<div>{{ $project->description }}</div>

					<a href="/projects">Go Back</a>
				</div> -->
			</div>
		</div>
	</main>

@endsection