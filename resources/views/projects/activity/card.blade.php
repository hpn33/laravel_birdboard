<div class="card mt-3">
	<ul class="text-xs list-rest">
		
		@foreach($project->activity as $activity)

			<li class="{{ $loop->last ? '' : 'mb-1'}}">
				@include('project.activity.{$activity->descriptoin}')
				<span class="text-gray">{{ $activity->create_at->diffForHumans(null, true) }}</span>
			</li>

		@endforeach

	</ul>
</div>
					