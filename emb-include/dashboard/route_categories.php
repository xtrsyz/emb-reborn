<?php

$content->page_title = 'Categories';
$content->body = "<pre>".htmlspecialchars(print_r($db->rows("SELECT * FROM embuh_category"), true))."</pre>";
