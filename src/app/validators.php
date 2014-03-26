<?php

Validator::extend('attachmentValidObjectId', function($attribute, $value, $parameters) {
    $result = false;
    $objectType = Input::get('link_type');
    $objectId = Input::get('link_id');
    $accountId = Authenticate::getCurrentAccountId();

    switch($objectType) {
    	case 'subscription':
    		if(Subscription::where('account_id', $accountId)->where('id', $objectId)->exists()) {
    			$result = true;
    		}
    		break;

    	case 'income':
    		if(Income::where('account_id', $accountId)->where('id', $objectId)->exists()) {
    			$result = true;
    		}
    		break;

    	case 'insurance':
    		if(Insurance::where('account_id', $accountId)->where('id', $objectId)->exists()) {
    			$result = true;
    		}
    		break;

    	case 'financial':
    		if(Financial::where('account_id', $accountId)->where('id', $objectId)->exists()) {
    			$result = true;
    		}
    		break;
    }

    return $result;
});


Validator::extend('validateFileFrombase64', function($attribute, $value, $parameters) {
	$result = false;
	$plain = Input::get($attribute);
	$mimeType = Attachment::mime($plain);
	if(in_array($mimeType, array('image/gif', 'image/jpeg', 'image/pjpeg', 'application/pdf', 'image/png'))) {
		$result = true;
	}
    return $result;

});

?>