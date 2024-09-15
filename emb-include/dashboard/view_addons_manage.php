<?php

$content->page_title = "Add-ons: {$params->target}";

if(in_array($params->target, $config->active_addons))
	include $content->dashboard_content_view_path ?: 'template_default-options-page.php';
else
	include 'view_default.php';
