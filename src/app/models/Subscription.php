<?php

class Subscription extends Eloquent {

	protected $table = 'subscription';

	public static $validationRules = array(
		'name' => 'required',
		'start' => 'required|date',
		'end' => 'sometimes|required|date|before:start'
	);

	public function attachments() {
		return $this->morphToMany('Attachment', 'attachable');
	}

	
}

?>