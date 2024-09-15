<?php

if(empty($params->POST->get())) return;
$post_id = $params->target ?: $params->POST->id;
if(!$post_id)
	return $dashboard->response('Post ID can not be empty', 'error');
$result = $app->post_publishPostNow($post_id);
$content->append($dashboard->getPostsCount());
return $result ? $dashboard->response('Post published') : $dashboard->response('Update post failed', 'error');
