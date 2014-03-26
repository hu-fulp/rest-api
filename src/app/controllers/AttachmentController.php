<?php

class AttachmentController extends Controller {


	public function postCreate() {
		$res = array();
		$validator = Validator::make(Input::all(), Attachment::$validationRules);
		if($validator->fails()) {
			$res['type'] = 'error';
			$res['validation_errors'] = $validator->messages()->toArray();
			$res['message'] = 'Attachment could not be created.';
		} else {

			// Upload file
			$salt = strtoupper(hash('sha512', uniqid().rand(0,50)));
			$pepper = strtoupper(hash('sha512', uniqid().rand(0,55)));
			$hash = substr(hash('sha512', uniqid().rand(0,999999)), 0, 100);
			$file = ($salt . Input::get('file') . $pepper );
			file_put_contents(storage_path().'/attachments/'.$hash, $file);

			// Insert file
			$obj = new Attachment();
			$obj->name = Input::get('name');
			$obj->mime = Attachment::mime(Input::get('file'));
			$obj->salt = $salt;
			$obj->pepper = $pepper;
			$obj->hash = $hash;
			$obj->save();
			$res['type'] = 'success';
			$res['message'] = 'Attachment has been created successfully.';
		}
		return ApiResponse::json($res);
	}

	public function getShow($hash) {
		$obj = Attachment::where('hash', $hash)->first();
		if(!$obj) {
			App::abort(404);
		}
		$file = str_replace(array($obj->pepper, $obj->salt), '', file_get_contents(storage_path().'/attachments/'.$obj->hash));
		echo $file;die();
		echo base64_decode($file);
		header('Content-Type: '.$obj->mime);
		exit();
	}

	public function postDelete() {

	}
}

?>