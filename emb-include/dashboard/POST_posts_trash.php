<?php

if(empty($params->POST->get())) return;
if($params->POST->ids) {
	$i = 0;
	foreach($params->POST->ids as $post_id) {
		if($params->POST->permanent)
			$app->post_deletePost($post_id);
		else
			$app->post_trashPost($post_id);
		$i++;
	}
	return $dashboard->response("$i posts moved to trash");
}
$post_id = $params->target ?: $params->POST->id;
if(!$post_id)
	return $dashboard->response('Post ID can not be empty', 'error');
$result = $app->post_trashPost($post_id);
$content->append($dashboard->getPostsCount());
return $result ? $dashboard->response('Post moved to trash') : $dashboard->response('Update post failed', 'error');
