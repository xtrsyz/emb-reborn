<div class="blog-pager" id="blog-pager">
<?php if($content->prev_page): ?><a class="pager" href="<?php echo $content->prev_page ?>">Newer Posts</a><?php endif ?>

<?php foreach($widget->paging ?: [] as $name => $widget_content): ?>
<?php foreach($widget_content['content'] as $i => $item): ?>
	<a class="pager" href="<?php echo $item['href'] ?>"><?php echo htmlspecialchars($item['text']) ?></a>
<?php endforeach ?>
<?php endforeach ?>

<?php if($content->next_page): ?><a class="pager" href="<?php echo $content->next_page ?>">Older Posts</a><?php endif ?>

</div>
