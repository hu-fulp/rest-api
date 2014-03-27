<?php

class UserController extends Controller {

	public function __construct() {
        $this->beforeFilter('auth', array('except' => array('postLogin', 'postRegister')));
	}


	public function postLogin() {
		$res = array();
		$validator = Validator::make(
			Input::all(),
			array(
			    'android_device_id' => 'required'
			)
		);
		if($validator->fails()) {
			$res['type'] = 'error';
			$res['validation_errors'] = $validator->messages()->toArray();
			$res['message'] = 'User could not be logged in.';
		} else {
			if($user = User::where('email', $_SERVER['PHP_AUTH_USER'])
				->where('password', hash('sha512', $_SERVER['PHP_AUTH_PW']))
				->first()) {
				$session = new Login();	
				$session->user_id = $user->id;
				$session->token = hash('sha512', uniqid().rand(0,999));
				$session->account_id = $user->accounts()->select('account.id')->lists('id')[0];
				$session->android_device_id = Input::get('android_device_id');
				$session->save();
				$res['type'] = 'success';
				$res['auth'] = array('user_id'=>$user->id, 'token'=>$session->token);
				$res['Account'] = $user->accounts()->select('account.id', 'account.name')->lists('name', 'id');
			} else {
				$res['type'] = 'error';
				$res['message'] = 'Invalid username and/or password';
			}
		}
		return ApiResponse::json($res);
	}

	public function postRegister() {
		$res = array();
		$validator = Validator::make(
			Input::all(),
			array(
			    'email' => 'required|email|unique:user',
			    'password'=>'required|min:3|confirmed',
			    'name'=>'required|min:1:'
			)
		);
		if($validator->fails()) {
			$res['type'] = 'error';
			$res['validation_errors'] = $validator->messages()->toArray();
			$res['message'] = 'Item not created';
		} else {
			$user = new User();
			$user->email = Input::get('email');
			$user->password = hash('sha512', Input::get('password'));
			$user->name = Input::get('name');
			$user->save();
			$account = new Account();
			$account->name = $user->name;
			$user->accounts()->save($account);
			$res['type'] = 'success';
			$res['message'] = 'Item created.';
			$res['item_id'] = $user->id;
		}
		return ApiResponse::json($res);
	}



	public function postUpdate() {
		$userId = $_SERVER['PHP_AUTH_PW'];
		$validator = Validator::make(
			Input::all(),
			array(
			    'password'=>'required|min:3|confirmed',
			    'name'=>'required|min:1:'
			)
		);
		if($validator->fails()) {
			$res['type'] = 'error';
			$res['validation_errors'] = $validator->messages()->toArray();
			$res['message'] = 'User not updated.';
		} else {
			$user = User::where('id', $userId)->first();
			$user->name = Input::get('name');
			$user->password = hash('sha512', Input::get('password'));
			$user->save();
			$res['type'] = 'success';
			$res['message'] = 'Item updated.';
		}
		return ApiResponse::json($res);
	}

	public function postSwitch() {
		$userId = $_SERVER['PHP_AUTH_PW'];
		$token = $_SERVER['PHP_AUTH_USER'];
		$validator = Validator::make(
			Input::all(),
			array(
			    'account_id'=>'required|exists:user_account,account_id,user_id,'.$userId
			)
		);
		if($validator->fails()) {
			$res['type'] = 'error';
			$res['validation_errors'] = $validator->messages()->toArray();
			$res['message'] = 'Account ID not updated.';
		} else {
			$login = Login::where('token', $token)->where('user_id', $userId)->first();
			$login->account_id = Input::get('account_id');
			$login->save();
			$res['type'] = 'success';
			$res['message'] = 'Account ID updated.';
		}
		return ApiResponse::json($res);
	}
}