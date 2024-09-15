<?php

spl_autoload_register(function($class) {
	require 'emb-include/class/'.$class.'.php';
});

define('APP_ROOT_PATH', Utilities::getRelativePath(getcwd(), __DIR__));

$app = new EmbuhBlog;
$app->run();
