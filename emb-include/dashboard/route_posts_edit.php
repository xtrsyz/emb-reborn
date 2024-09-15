<?php

// $post_id = $params->target;
if(!$params->target)
	return $dashboard->response('Post ID can not be empty', 'error');
$old_post = $app->post_getPostById($params->target);
if(empty($old_post))
	return $dashboard->response('Post not found', 'error');
$old_post['tags']       = implode(', ', array_column($old_post['tags'], 'title'));
$old_post['categories'] = implode(', ', array_column($old_post['categories'], 'title'));
// $old_post['meta']     = new Collection($old_post['meta']);
foreach($old_post as $key => $value)
	$content->{"post_editor_$key"} = $value;
$content->post_editor_datetime_published = gmdate('Y-m-d\TH:i:s', $content->post_editor_date_published);
if($params->POST->get()) {
	// $params->POST->published = $params->POST->draft ? 0 : 1;
	// foreach($params->POST->get() as $key => $value) {
	// 	if(array_key_exists($key, $old_post))
	// 		$update[$key] = $value;
	// }
	// if(!$app->post_updatePostFromArray($post_id, $update, $message))
	// 	return $dashboard->response($message, 'error');
	// $new_post = $app->post_getPostById($post_id);
	// $new_post['tags']       = implode(', ', array_column($new_post['tags'], 'title'));
	// $new_post['categories'] = implode(', ', array_column($new_post['categories'], 'title'));
	// // $new_post['meta']     = new Collection($new_post['meta']);
	// foreach($new_post as $key => $value)
	// 	$content->{"post_editor_$key"} = $value;
	// $dashboard->response('Post updated');
} elseif($params->GET->from_create) {
	$dashboard->response('Post created.');
}

$content->post_editor_card_title = 'Edit Post';
$content->dashboard_content_view_path = 'posts_edit';
include 'route_posts.php';
