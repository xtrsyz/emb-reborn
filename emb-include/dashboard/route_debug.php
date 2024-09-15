<?php

$content->page_title = 'Debug';
$content->body = "<pre>".htmlspecialchars(print_r($app, true))."</pre>";
// $dashboard->view();
