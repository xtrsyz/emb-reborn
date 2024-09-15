<?php

$content->title = $content->title ?: '404 Not Found';
$content->header_meta_robots = 'noindex,follow';
$app->render('404');
