<?php

class ApiResponse extends Eloquent {

	public static function json($res) {
		$httpCode = $res['type'] == 'error' ? 400 : 200;
		if(isset($res['httpCode'])) {
			$httpCode = $res['httpCode'];
			unset($res['httpCode']);
		}
		return Response::json($res, $httpCode);
	}

}

?>