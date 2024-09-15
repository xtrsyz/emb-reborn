<?php

$data = json_decode($app->params->POST->json ?: "[]", true);
if(empty($data))
	return $app->JSONResponse([], 'Data is empty or invalid JSON', 400);
$keys = array_keys($data);
if(array_keys($keys) === $keys) {
	// multiple post bulk
	$added = $updated = $ignored = 0;
	foreach($data as $item) {
		$item = $dashboard->__helper_posts_bulk_maintain_item($item);
		if(empty($item)) {
			$ignored++;
			continue;
		}
		if($app->post_createPostFromArray($item))
			$added++;
		elseif($app->params->POST->update_on_duplicate)
			$app->post_updatePostFromArray($app->post_getPostIdBySlug(isset($item['slug']) ? $item['slug'] : $app->slug($item['title'])), $item) ? $updated++ : $ignored++;
		else
			$ignored++;
	}
	return $app->JSONResponse($dashboard->getPostsCount(), "{$added} posts added, {$updated} updated, {$ignored} ignored.");
} else {
	// single post bulk
	$item = $dashboard->__helper_posts_bulk_maintain_item($data);
	if(empty($item))
		return $app->JSONResponse([], "Post filtered and ignored, nothing happen.");
	elseif($app->post_createPostFromArray($item, $message))
		$msg = "New post added.";
	elseif($app->params->POST->update_on_duplicate)
		$msg = $app->post_updatePostFromArray($app->post_getPostIdBySlug(isset($item['slug']) ? $item['slug'] : $app->slug($item['title'])), $item, $message)
		? "Post with same slug exists, content updated."
		: $message;
	else
		return $app->JSONResponse([], "$message, nothing happen." ?: "Failed to add post, nothing happen.");
	return $app->JSONResponse($dashboard->getPostsCount(), $msg);
}
