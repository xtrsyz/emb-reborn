<?php

if($params->GET->activated)
	$dashboard->response("Add-ons {$params->GET->activated} is now active");
if($params->GET->deactivated)
	$dashboard->response("Add-ons {$params->GET->deactivated} is now inactive");
if($params->GET->invalid)
	$dashboard->response("Add-ons {$params->GET->invalid} is invalid", "error");

$content->page_title = 'Add-ons';
