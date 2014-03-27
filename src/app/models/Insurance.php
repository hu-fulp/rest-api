<?php

class Insurance extends Eloquent {

	protected $table = 'insurance';

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