<?php

if($params->REQUEST->key) {
	$key = $params->REQUEST->key;
	$value = $config->{$key};
	if($params->REQUEST->from == 'delete') {
		if($value === null)
			$app->JSONResponse(['act' => 'delete', 'key' => $key, 'value' => $value], "[$key] deleted.");
		$app->JSONResponse(['act' => 'update', 'key' => $key, 'value' => $value], "[$key] using default value.");
	}
	$app->JSONResponse(['key' => $key, 'value' => $value]);
}
if(is_array($params->REQUEST->keys)) {
	$responses = [];
	foreach($params->REQUEST->keys as $key)
		$responses[] = ['key' => $key, 'value' => $config->{$key}];
	$app->JSONResponse($responses);
}
$app->JSONResponse([], "Invalid parameter.", 400);
