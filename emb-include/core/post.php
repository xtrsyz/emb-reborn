<?php

$post_params = $app->filter('core_post_params', ['type' => 'post', 'is_published' => true]);
if($params->title)  $post = $app->post_getPostBySlug($params->title, $post_params);
elseif($params->id) $post = $app->post_getPostById($params->id, $post_params);
else                $post = [];

$content->append($app->filter('core_post', $post));
$app->action('content_ready');

if(empty($content->get())) {
	$content->title = 'Post Not Found';
	$content->header_meta_robots = 'noindex, follow';
	$app->invoke('404');
	return;
}

$app->preparePost($content);

$content->document_title                 = $app->parse($config->site_post_document_title ?: "{{title}} - {{config.site_name}}", $content->get());
$content->page_title                     = $app->parse($config->site_post_page_title ?: "{{title}}", $content->get());
$content->header_meta_robots             = $config->meta_robots_post ?: 'noindex, follow';
$content->header_meta_description        = strip_tags($content->summary);
$content->{'header_meta_og:title'}       = $content->page_title;
$content->{'header_meta_og:description'} = $content->header_meta_description;
$content->{'header_meta_og:url'}         = $content->permalink;
$content->{'header_meta_og:image'}       = $content->meta_images;

$widget->set('breadcrumb', 'breadcrumb')->addLink('Home', $app->homepath);
foreach($content->categories ?: [] as $category)
	$widget->addLink($category['title'], $category['permalink']);
$widget->addItem($content->title);

$widget->set('post_footer', 'tags');
foreach($content->tags ?: [] as $tag)
	$widget->addLink($tag['title'], $tag['permalink']);

$app->action('done');
$app->render('post');
