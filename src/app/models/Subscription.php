<?php

class Subscription extends Eloquent {

	protected $table = 'subscription';

	public static $validationRules = array(
		'name' => 'required',
		'start' => 'required|date|before:end',
		'end' => 'sometimes|required|date',
		'interval' => 'required',
		'amount'=>'required|numeric'

	);

	public function attachments() {
		return $this->morphMany('Attachment', 'attachable');
	}

	
}

?>