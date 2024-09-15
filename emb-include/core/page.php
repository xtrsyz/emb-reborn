<?php

$post_params = $app->filter('core_post_params', ['type' => 'page', 'is_published' => true]);
if($params->title)  $post = $app->post_getPostBySlug($params->title, $post_params);
elseif($params->id) $post = $app->post_getPostById($params->id, $post_params);
else                $post = [];

$content->data($app->filter('core_get_post', $post));
$app->action('content_ready');

if(empty($content->get())) {
	$content->title = 'Page Not Found';
	$content->header_meta_robots = 'noindex, follow';
	$app->invoke('404');
	return;
}

$app->preparePost($content);

$content->document_title                 = $app->parse($config->site_page_document_title ?: "{{title}} - {{config.site_name}}", $content->get());
$content->page_title                     = $app->parse($config->site_page_page_title ?: "{{title}}", $content->get());
$content->header_meta_robots             = $config->meta_robots_page ?: 'noindex, follow';
$content->header_meta_description        = strip_tags($content->summary);
$content->{'header_meta_og:title'}       = $content->page_title;
$content->{'header_meta_og:description'} = $content->header_meta_description;
$content->{'header_meta_og:url'}         = $content->permalink;
$content->{'header_meta_og:image'}       = $content->meta_images;

$widget->set('breadcrumb', 'breadcrumb')->addLink('Home', $app->homepath);
$widget->addItem($content->title);

$app->action('done');
$app->render('page');
