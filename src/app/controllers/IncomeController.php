<?php

class IncomeController extends Controller {

	public function getIndex() {
		$obj = Income::where('account_id', Authenticate::getCurrentAccountId())->get();
		$res['type'] = 'success';
		$res['items'] = $obj->toArray();
		return ApiResponse::json($res);
	}

	public function postCreate() {
		$res = array();
		$validator = Validator::make(Input::all(), Income::$validationRules);
		if($validator->fails()) {
			$res['type'] = 'error';
			$res['validation_errors'] = $validator->messages()->toArray();
			$res['message'] = 'Item not created.';
		} else {
			$obj = new Income();
			$obj->name = Input::get('name');
			$obj->start = Input::get('start');
			$obj->interval = Input::get('interval');
			$obj->type = Input::get('type');
			$obj->end = Input::get('end');
			$obj->amount = Input::get('amount');
			$obj->account_id = Authenticate::getCurrentAccountId();
			$obj->save();
			$res['type'] = 'success';
			$res['message'] = 'Item created.';
			$res['item_id'] = $obj->id;
		}
		return ApiResponse::json($res);
	}

	public function getRead($id) {
		$res = array();
		if($obj = Income::where('id', $id)->where('account_id', Authenticate::getCurrentAccountId())->first()) {
			$res['item'] = $obj->toArray();
			$res['item']['attachments'] = $obj->attachments->toArray();
			$res['type'] = 'success';
		} else {
			$res['type'] = 'error';
			$res['message'] = 'Item does not exists.';
			$res['httpCode'] = 404;
		}
		return ApiResponse::json($res);
	}

	public function postUpdate($id) {
		$res = array();
		if($obj = Income::where('id', $id)->where('account_id', Authenticate::getCurrentAccountId())->first()) {

			$validator = Validator::make(Input::all(), Income::$validationRules);
			if($validator->fails()) {
				$res['type'] = 'error';
				$res['validation_errors'] = $validator->messages()->toArray();
				$res['message'] = 'Item not updated.';
			} else {
				$obj->name = Input::get('name');
				$obj->start = Input::get('start');
				$obj->interval = Input::get('interval');
				$obj->type = Input::get('type');
				$obj->end = Input::get('end');
				$obj->amount = Input::get('amount');
				$obj->account_id = Authenticate::getCurrentAccountId();
				$obj->save();
				$res['type'] = 'success';
				$res['message'] = 'Item updated.';
			}

		} else {
			$res['type'] = 'error';
			$res['httpCode'] = 404;
			$res['message'] = 'Item does not exists.';
		}
		return ApiResponse::json($res);
	}

	public function postDelete() {
		$id = Input::get('id');
		if($obj = Income::where('id', $id)->where('account_id', Authenticate::getCurrentAccountId())->first()) {
			$obj->delete();
			$res['type'] = 'success';
			$res['message'] = 'Item deleted.';
		} else {
			$res['type'] = 'error';
			$res['message'] = 'Item does not exists.';
			$res['httpCode'] = 404;
		}
		return ApiResponse::json($res);
	}
}

?>