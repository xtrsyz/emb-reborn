<?php

// $post_id = $params->target
if(!$params->target)
	return; // $dashboard->response('Post ID can not be empty', 'error');
$old_post = $app->post_getPostById($params->target);

$params->POST->published = $params->POST->draft ? 0 : 1;
$update = [];
foreach($params->POST->get() as $key => $value) {
	if(array_key_exists($key, $old_post))
		$update[$key] = $value;
}
if(!$app->post_updatePostFromArray($params->target, $update, $message))
	return $dashboard->response($message, 'error');
$new_post = $app->post_getPostById($params->target);
$new_post['tags']       = implode(', ', array_column($new_post['tags'], 'title'));
$new_post['categories'] = implode(', ', array_column($new_post['categories'], 'title'));
// $new_post['meta']     = new Collection($new_post['meta']);
foreach($new_post as $key => $value)
	$content->{"post_editor_$key"} = $value;
$dashboard->response('Post updated');
