<?php

if(php_sapi_name() != "cli") return;
ob_start();
$app->cli_buffer("== EmbuhBlog PHP-CLI Utilities ==");
!$params->cli_verbose or $app->cli_buffer("Binary path     : ".PHP_BINARY);
!$params->cli_verbose or $app->cli_buffer("PHP-CLI Version : ".phpversion());
!$params->cli_verbose or $app->cli_buffer("CWD             : ".getcwd());
!$params->cli_verbose or $app->cli_buffer("Hostname        : {$app->domain}");
                         $app->cli_buffer("URL             : {$app->fullpath}");
                         $app->cli_buffer("Action          : {$params->cli_action}");

if(!$params->cli_action)
	return;
if($params->cli_action == 'debug') {
	echo "\$_SERVER:\n";
	print_r($_SERVER);
	echo "\$app:\n";
	print_r(get_object_vars($app));
	echo "\$params:\n";
	print_r($params->filter(function($v, $k) { return !ctype_upper($k); })->get());
}
$app->action($params->cli_action);
