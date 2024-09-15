<?php

$app->addProperty('preparePost', function(Collection &$post) use ($app) {
	$post->content = Parsedown::instance()->text($app->parse($post->content, $post->get()));
	$post->summary = $app->parse($post->summary ?: mb_substr(strip_tags($post->content), 0, 200), $post->get());
	preg_match_all('/<img(?:[^>]*\s)?\bsrc\s*=\s*[\'"]\K(.*?)(?=[\'"])/', $post->content, $matches_img);
	if(!$post->meta_images || !$post->meta_thumbnail) {
		if(!empty($matches_img[0][0])) {
			$post->meta_thumbnail = $post->meta_thumbnail ?: $matches_img[0][0];
			$post->meta_images    = $post->meta_images ?: $matches_img[0];
		}
		$post->meta_images    = $post->meta_images ?: ($post->meta_thumbnail ? [$post->meta_thumbnail] : []);
		$post->meta_thumbnail = $post->meta_thumbnail ?: current($post->meta_images);
	}
});
