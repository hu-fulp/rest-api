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
			file_put_contents(storage_path().'/attachments/'.$hash, Attachment::mcEncrypt($file));

			// Insert file
			$obj = new Attachment();
			$obj->name = Input::get('name');
			$obj->mime = Attachment::mime(Input::get('file'));
			$obj->salt = $salt;
			$obj->pepper = $pepper;
			$obj->hash = $hash;
			$obj->attachable_type = Input::get('link_type');
			$obj->attachable_id = Input::get('link_id');
			$obj->save();
			$res['type'] = 'success';
			$res['message'] = 'Attachment has been created successfully.';
			$res['attachment_hash'] = $hash;
		}
		return ApiResponse::json($res);
	}

	public function getShow($hash) {

		$obj = Attachment::where('hash', $hash)->first();
		if(!$obj) {
			App::abort(404);
		}
		$file = file_get_contents(storage_path().'/attachments/'.$obj->hash);
		$file = Attachment::mcDecrypt($file);
		$file = str_replace(array($obj->pepper, $obj->salt), '', $file);
		header('Content-Type: '.$obj->mime);
		exit(base64_decode($file));
	}

	public function postDelete() {

	}
}

?>