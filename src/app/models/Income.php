<?php

class Income extends Eloquent {

	protected $table = 'income';

	public static $validationRules = array(
		'name' => 'required',
		'start' => 'required|date|before:end',
		'end' => 'sometimes|required|date',
		'interval' => 'required',
		'type' => 'required',
		'amount'=>'required|numeric'
	);

	public function attachments() {
		return $this->morphMany('Attachment', 'attachable');
	}

	
}

?>