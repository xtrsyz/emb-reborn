<?php

$post_id = $params->target ?: $params->POST->id;
if(!$post_id)
	return $dashboard->response('Post ID can not be empty', 'error');
$result = $app->post_draftPost($post_id);
$content->append($dashboard->getPostsCount());
return $result ? $dashboard->response('Post moved to draft') : $dashboard->response('Update post failed', 'error');
