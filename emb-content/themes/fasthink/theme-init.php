<?php

if($app->route == 'post' || $app->route == 'page') {
	// set img div wrapper
	$content->content = preg_replace(
		'/(?:<p>)?(<img[^>]+>)(?:<\/p>)?/',
		'<div class="separator" style="clear: both; text-align: center;">$1</div>',
		$content->content
	);
	// highlight_string(print_r($content->data(), true));
}

$app->addProperty('printHeaderMeta', function ($indent = "\t\t") use($app) {
	$meta = [];
	foreach($app->config->get() as $key => $value)
		if(strpos($key, 'site_meta_') === 0) $meta[substr($key, 10)] = $value;
	foreach($app->content->get() as $key => $value)
		if(strpos($key, 'header_meta_') === 0) $meta[substr($key, 12)] = $value;
	foreach($meta as $name => $content) {
		$attr = strpos($name, 'og:') === 0 ? 'property' : 'name';
		if(is_array($content))
			foreach($content as $sub_content)
				echo $indent.'<meta '.$attr.'="'.$name.'" content="'.htmlspecialchars($sub_content).'">'."\n";
		else
			echo $indent.'<meta '.$attr.'="'.$name.'" content="'.htmlspecialchars($content).'">'."\n";
	}
});
