<?php

$widget->set('dashboard', 'breadcrumb')->addItem('Adminer');
$content->page_title = 'Adminer';
$content->card_title = 'DB Manager by Adminer Add-ons';
$content->card_config_editor_hide = true;
$content->card_params_hide = true;
$content->card_extra_start = [[
	'title' => 'Database List',
	'body_path' => ADMINER_DIR.'/template-adminer-index.php'
]];
