<?php

class Attachment extends Eloquent {

	protected $table = 'attachment';

	public static $validationRules = array(
		'name'=>'required',
		'file' => 'required|validateFileFrombase64',
		'link_type' => 'required|in:financial,insurance,income,subscription',
		'link_id' => 'required|attachmentValidObjectId'
	);

	public static function mime($str) {
		$str = base64_decode($str);
		$f = finfo_open();
		return finfo_buffer($f, $str, FILEINFO_MIME_TYPE);
	}


	
}

?>