<?php

if(!$params->POST->password_old)
	$dashboard->response("Old password is required", 'error');
elseif(!$params->POST->password_new)
	$dashboard->response("New password is required", 'error');
elseif(!$params->POST->password_new || $params->POST->password_new != $params->POST->password_new_confirm)
	$dashboard->response("Please confirm new password", 'error');
elseif(!password_verify($params->POST->password_old, $user['password']))
	$dashboard->response("Old password invalid", 'error');
elseif($app->user_updatePassword($user['id'], $params->POST->password_new))
	$dashboard->response('Password updated');
else
	$dashboard->response("An error occured, nothing changed", 'error');
