<?php

$widget->set('dashboard', 'breadcrumb')
	->addLink('Profile', $dashboard->URI($params->route))
	->addLink('Reset Password', $dashboard->URI($params->routes));

$content->page_title = 'Reset Password';
// $dashboard->view();
