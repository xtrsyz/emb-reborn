<?php

$content->post_editor_author_id       = $new['author_id']      = $params->POST->author_id ?: 1;
$content->post_editor_title           = $new['title']          = $params->POST->title;
$content->post_editor_slug            = $new['slug']           = $app->slug($params->POST->slug);
$content->post_editor_content         = $new['content']        = $params->POST->content;
$content->post_editor_summary         = $new['summary']        = $params->POST->summary ?: null;
$content->post_editor_type            = $new['type']           = $params->POST->type ?: 'page';
$content->post_editor_published       = $new['published']      = $params->POST->draft ? 0 : 1;
$content->post_editor_date_published  = $new['date_published'] = $params->POST->date_published ?: ($new['published'] ? time() : null);

foreach($params->POST->get() as $key => $value)
	if(strpos($key, 'meta_') === 0)
		$content->{"post_editor_$key"} = $new[$key] = $value;
$new['meta']                          = $params->POST->meta ?: [];
$content->post_editor_meta            = new Collection($new['meta']);

$post_id = $app->post_createPostFromArray($new, $message);
if(!$post_id)
	return $dashboard->response($message, 'error');
foreach($content->get() as $key => $value)
	if(strpos($key, 'post_editor_') === 0)
		unset($content->{$key});
$location = $dashboard->URI('pages', 'edit', $post_id);
header("Location: {$location}?from_create=true");
exit;
