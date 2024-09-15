<?php

if(!empty($params->POST->options)) {
	// multiple options set
	$responses = [];
	foreach($params->POST->options as $option) {
		$key = trim($option['key']);
		$old_value = $app->opt_getOption($key);
		$new_value = $app->fixTypeGet($option['value']);
		if(is_null($new_value)) {
			$app->opt_deleteOption($key);
			$responses[] = ['act' => 'delete', 'key' => $key, 'value' => $new_value];
		} elseif($old_value === $new_value) {
			$responses[] = ['act' => 'nothing', 'key' => $key, 'value' => $new_value];
		} else {
			$app->opt_setOption($key, $new_value);
			if($old_value !== null)
				$responses[] = ['act' => 'update', 'key' => $key, 'value' => $new_value];
			else
				$responses[] = ['act' => 'add', 'key' => $key, 'value' => $new_value];
		}
	}
	$app->JSONResponse($responses, "Change saved.");
} else {
	// single option set
	$key = trim($params->POST->key) ?: trim($params->POST->prefix).trim($params->POST->name);
	if(!$key)
		$app->JSONResponse($params->POST->get(), "key is empty.", 400);
	$old_value = $app->opt_getOption($key);
	$new_value = $app->fixTypeGet($params->POST->value);
	if(is_null($new_value)) {
		$app->opt_deleteOption($key);
		$app->JSONResponse(['act' => 'delete', 'key' => $key, 'value' => $new_value], "[$key] deleted. Default value will be used if exists.");
	} elseif($old_value === $new_value) {
		$app->JSONResponse(['act' => 'nothing', 'key' => $key, 'value' => $new_value], "[$key] Nothing changed.");
	} else {
		$app->opt_setOption($key, $new_value);
		if($old_value !== null)
			$app->JSONResponse(['act' => 'update', 'key' => $key, 'value' => $new_value], "[$key] updated.");
		else
			$app->JSONResponse(['act' => 'add', 'key' => $key, 'value' => $new_value], "[$key] added.");
	}
}