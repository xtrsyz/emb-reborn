<?php
$tag = $app->tag_getTagBySlug($params->title);
if(empty($tag)) {
	$content->title = 'Tag Not Found';
	$content->body = 'Please choose valid category.';
	$app->invoke('404');
	return;
}

$content->title = $tag['title'];

$post_params = ['type' => 'post', 'is_published' => true, 'tag_id' => $tag['id']];
$limit = $config->site_item_per_page;
$offset = -1;
if(!$params->pagenum) {
	$content->next_page = $app->createPermalink('tag_paged', ['pagenum' => 2, 'title' => $params->title]);
	$content->document_title = 'Tag '.$content->title.' - '.$config->site_name;
	$content->header_meta_robots = $config->meta_robots_category ?: 'noindex, follow';
} else {
	$offset = ($params->pagenum-1)*$limit;
	$content->prev_page = $app->createPermalink('tag_paged', ['pagenum' => $params->pagenum-1, 'title' => $params->title]);
	$content->next_page = $app->createPermalink('tag_paged', ['pagenum' => $params->pagenum+1, 'title' => $params->title]);
	if($params->pagenum == 2)
		$content->prev_page = $app->createPermalink('tag', ['title' => $params->title]);
	$content->document_title = 'Tag '.$content->title.' Page #'.$params->pagenum.' - '.$config->site_name;
	$content->header_meta_robots = $config->meta_robots_tag_paged ?: 'noindex, follow';
}

$content->header_meta_description = $config->site_description ?: $config->site_tagline;
// $content->{'header_meta_og:title'} = $config->site_name;
// $content->{'header_meta_og:description'} = $content->header_meta_description;
// $content->{'header_meta_og:url'} = $app->fullpath;

$posts = $app->post_getPosts($post_params, $limit, $offset);
foreach($posts as &$post)
	$post = new Collection($post);
$content->items = $posts;
$app->action('content_ready');
if(empty($content->items))
	$content->header_meta_robots = 'noindex,follow';
foreach($content->items as &$post)
	$app->preparePost($post);
if(count($content->items) < $limit)
	unset($content->next_page);

$app->action('done');
$app->render('tag');
