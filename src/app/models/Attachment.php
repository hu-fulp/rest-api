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

	public static function mcEncrypt($encrypt) {
		$key = Config::get('encryption.key');
	    $encrypt = serialize($encrypt);
	    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
	    $key = pack('H*', $key);
	    $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
	    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
	    $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
	    return $encoded;
	}
 
	public static function mcDecrypt($decrypt) {
		$key = Config::get('encryption.key');
	    $decrypt = explode('|', $decrypt);
	    $decoded = base64_decode($decrypt[0]);
	    $iv = base64_decode($decrypt[1]);
	    $key = pack('H*', $key);
	    $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
	    $mac = substr($decrypted, -64);
	    $decrypted = substr($decrypted, 0, -64);
	    $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
	    if($calcmac!==$mac){ return false; }
	    $decrypted = unserialize($decrypted);
	    return $decrypted;
	}
}

?>