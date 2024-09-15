<?php if($content->dashboard_content_view_html): ?>
<?php echo $content->dashboard_content_view_html ?>
<?php elseif($content->dashboard_content_view_path && file_exists($content->dashboard_content_view_path)): ?>
<?php $app->invoke($content->dashboard_content_view_path) ?>
<?php elseif($content->dashboard_content_view_path_md && file_exists($content->dashboard_content_view_path_md)): ?>
<?php echo Parsedown::instance()->text($app->parse(file_get_contents($content->dashboard_content_view_path_md))) ?>
<?php else: ?>
<div class="card mb-2">
	<div class="card-header"><h5 class="card-title"><?php echo $content->card_title ?: $content->page_title ?></h5></div>
	<div class="card-body">
<?php if($content->body)             : echo $content->body ?>
<?php elseif($content->body_path)    : $app->invoke($content->body_path) ?>
<?php elseif($content->body_md)      : echo Parsedown::instance()->text($content->body_md) ?>
<?php elseif($content->body_md_path) : echo Parsedown::instance()->text($app->parse(file_get_contents($content->body_md_path))) ?>
<?php else: ?>
<pre>-- Unknown dashboard route --
-- Invalid request --
-- $content-&gt;dashboard_content_view_html is not set --
-- $content-&gt;dashboard_content_view_path is not set or not exists --
</pre>
<?php highlight_string(print_r($params->get(), true)) ?>
<?php endif ?>
	</div>
</div>
<?php endif ?>