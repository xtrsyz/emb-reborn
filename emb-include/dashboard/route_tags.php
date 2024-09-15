<?php

$content->page_title = 'Tags';
$content->body = "<pre>".htmlspecialchars(print_r($db->rows("SELECT * FROM embuh_tag"), true))."</pre>";
