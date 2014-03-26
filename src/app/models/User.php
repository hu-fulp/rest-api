<?php

class User extends Eloquent {

	protected $table = 'user';

	public function accounts() {
		return $this->belongsToMany('Account',  'user_account', 'user_id', 'account_id');
	}

	public static function auth() {
		$result = true;
		


		return $result;
	}
}

?>