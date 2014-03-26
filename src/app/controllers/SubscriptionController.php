<?php

class SubscriptionController extends Controller {

	public function postIndex() {

	}

	public function postCreate() {
		$res = array();
		$validator = Validator::make(Input::all(), Subscription::$validationRules);
		if($validator->fails()) {
			$res['type'] = 'error';
			$res['validation_errors'] = $validator->messages()->toArray();
			$res['message'] = 'Subscription could not be created.';
		} else {
			$obj = new Subscription();
			$obj->name = Input::get('name');
			$obj->start = Input::get('start');
			$obj->end = Input::get('end');
			$obj->save();
			$res['type'] = 'success';
			$res['message'] = 'Subscription has been created successfully.';
		}
		return Response::json($res);
	}

	public function getRead($id) {

	}

	public function postUpdate() {

	}

	public function getDelete($id) {

	}
}

?>