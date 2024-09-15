<?php

if($params->method == 'activate' && $params->target) {
	if(!file_exists($config->path_addons."/{$params->target}/index.php"))
		header("Location: ".$dashboard->URI('addons')."?invalid=".$params->target);
	elseif($dashboard->addons_activate($params->target) !== false)
		header("Location: ".$dashboard->URI('addons')."?activated=".$params->target);
	else
		header("Location: ".$dashboard->URI('addons')."?an_error_occured");
	exit;
} elseif($params->method == 'deactivate' && $params->target) {
	if(!file_exists($config->path_addons."/{$params->target}/index.php"))
		header("Location: ".$dashboard->URI('addons')."?invalid=".$params->target);
	elseif($dashboard->addons_deactivate($params->target) !== false)
		header("Location: ".$dashboard->URI('addons')."?deactivated=".$params->target);
	else
		header("Location: ".$dashboard->URI('addons')."?an_error_occured");
	exit;
} elseif(!$params->target) {
	header("Location: ".$dashboard->URI('addons')."?invalid=empty");
	exit;
}