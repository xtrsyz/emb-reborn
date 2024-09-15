<?php

$_GET['sqlite'] = '';
$_GET['username'] = $user['username'];

function adminer_object() {
	global $app;
	include_once "plugin.php";
	include_once "login-password-less.php";
	return new AdminerPlugin(array(
		new AdminerLoginPasswordLess($app->user['password']),
	));
}

include "Adminer.php";
exit;
