<?php

namespace App;

use App\Activity;

trait TriggersActivity {



	/** Boot the trait. */
	public static function bootRecordsActivity()
	{

		foreach ($static::getModelEventsToRecord() as $event) {
			static::$event(function($model) use ($event) {
				$model->recordActivity(
					$model->formatActivityDescription($event)
				);
			});
		}

	}


	// Record activity for the model.
	public function recordActivity($description)
	{

		$this
			->activitySubject()
			->activity()
			->create(compact('description'));

	}


	/** The activity feed for the project. */
	public function activity()
	{

		return $this->hasMany(Activity::class);

	}


	// Get the subject for the activity recording.
	protected function activitySubject()
	{

		return $this;

	}


	/** Get the model event that should trigger activity recording. */
	protected static function getModelEventsToRecord()
	{

		if(isset(static::$modelEventsToRecord)){
			return static::$modelEventsToRecord;
		}


		return ['created', 'updated', 'deleted'];

	} 


	/** Format the activity description */
	protected function formatActivityDescription($event)
	{

		return "{$event}_" .strtolower(class_basename($this));

	}


}