<?php

$glob = glob(APP_ROOT_PATH."/{$config->path_addons}/[!_]*/index.php");
$active = $config->active_addons ?: [];
$names = [];
$action = [];
foreach($glob as $path)
	$names[] = basename(dirname($path));
$addons = array_merge($active, array_diff($names, $active));
foreach($addons as $name) {
	if(in_array($name, $active))
		$action[] = '<a href="'.$dashboard->URI('addons/manage', $name).'"><button class="btn btn-sm btn-primary rounded-0 py-0">Manage</button></a> <form action="'.$dashboard->URI('addons/deactivate', $name).'" method="POST" class="d-inline"><button data-addons-deactivate class="btn btn-sm btn-warning rounded-0 py-0">Deactivate</button></form>';
	else
		$action[] = '<button class="btn btn-sm btn-primary rounded-0 py-0" disabled>Manage</button> <form action="'.$dashboard->URI('addons/activate', $name).'" method="POST" class="d-inline"><button data-addons-activate class="btn btn-sm btn-success rounded-0 py-0">Activate</button></form>';
}

$dashboard->__helper_invoke_card([
	'header_class' => 'text-bg-primary',
	'title' => 'Manage Add-ons',
	'table' => [
		'class' => 'text-nowrap',
		// 'header' => ['Hierarchy', 'Name', 'Action'],
		'header_row_html' => '<tr><th role="button" style="width:150px">Hierarchy</th><th role="button" class="w-100">Name</th><th role="button">Action</th></tr>',
		'body' => array_map(function($i, $name, $action) {
			return [$i, $name, $action];
		}, range(1, count($active)), $addons, $action),
	],
]);
?>
