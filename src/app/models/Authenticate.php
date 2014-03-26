<?php

class Authenticate extends Eloquent {

	protected $table = 'user';

	private static $accountId;

	public static function auth() {
		$result = false;
		if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$token = $_SERVER['PHP_AUTH_USER'];
			$userId = $_SERVER['PHP_AUTH_PW'];
			if($obj = Login::where('user_id', $userId)->where('token', $token)->first()) {
				$result = true;
			}
			self::$accountId = $obj->account_id;
		}
		return $result;
	}


	public static function getCurrentAccountId() {
		return self::$accountId;
	}
}

?>