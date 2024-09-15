<?php

$post_params = ['type' => 'post', 'is_published' => true];
$limit = $config->site_item_per_page;
$offset = 0;
if((int)$params->pagenum < 2) {
	$content->next_page = $app->createPermalink('home_paged', ['pagenum' => 2]);
	$content->document_title = $config->site_name.' - '.$config->site_tagline;
	$content->header_meta_robots = $config->meta_robots_home ?: 'noindex, follow';
} else {
	$offset = ($params->pagenum-1)*$limit;
	$content->prev_page = $app->createPermalink('home_paged', ['pagenum' => $params->pagenum-1]);
	$content->next_page = $app->createPermalink('home_paged', ['pagenum' => $params->pagenum+1]);
	if($params->pagenum == 2)
		$content->prev_page = $app->homepath;
	$content->document_title = 'Page #'.$params->pagenum.' - '.$config->site_name;
	$content->header_meta_robots = $config->meta_robots_home_paged ?: 'noindex, follow';
}

$content->header_meta_description = $config->site_description ?: $config->site_tagline;
$content->{'header_meta_og:title'} = $config->site_name;
$content->{'header_meta_og:description'} = $content->header_meta_description;
$content->{'header_meta_og:url'} = $app->fullpath;

$posts = $app->post_getPosts($post_params, $limit, $offset);
foreach($posts as &$post)
	$post = new Collection($post);
$content->items = $posts;

$app->action('content_ready');

foreach($content->items as &$post)
	$app->preparePost($post);
if(count($content->items) < $limit)
	unset($content->next_page);

$app->action('done');
$app->render('home');
