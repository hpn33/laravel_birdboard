		
@csrf

		<div class="field md-6">
			<label class="label text-sm md-2 block" for="title">Title</label>
			
			<div class="control">
				<input 
					type="text" 
					class="input bg-transparent border border-grey-light rounded p-2 text-xs w-full" 
					name="title" 
					placeholder="My next awesome project"
					value="{{ $project->title }}"
					required>
			</div>
				
		</div>

		<div class="field md-6">
			<label class="label text-sm md-2 block" for="description">Description</label>
			
			<div class="control">
				<textarea 
					type="text" 
					row="10"
					class="input bg-transparent border border-grey-light rounded p-2 text-xs w-full" 
					name="description" 
					placeholder="I should start learning piano."
					required>{{ $project->description }}</textarea>
			</div>
				
		</div>

		<div class="field md-6">
			<div class="control">
				<button type="submit" class="button is-link mr-2">{{ $buttonText }}</button>
				<a href="{{ $project->path() }}">Cancel</a>
			</div>				
		</div>		

@include('errors')