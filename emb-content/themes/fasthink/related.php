<?php if(!empty($related_items = $widget->getContent('related_post', 'related_post'))): ?>
<div id="related-posts"><h2>Related Post of <?php echo htmlspecialchars($content->title) ?></h2>
<div style="clear: both;">
<?php foreach($related_items ?: [] as $key => $item): ?>
<a style="text-decoration:none;margin:0 17px 10px 0;float:left;" href="<?php echo $item['href'] ?>" title="<?php echo htmlspecialchars($item['text']) ?>"><img alt="<?php echo htmlspecialchars($item['text']) ?>" class="related_img" src="<?php echo isset($item['image_url']) ? $item['image_url'] : 'https://2.bp.blogspot.com/-ex3V86fj4dQ/UrCQQa4cLsI/AAAAAAAAFdA/j2FCTmGOrog/s1600/no-thumbnail.png' ?>"><div id="related-title"><?php echo htmlspecialchars($item['text']) ?></div></a>
<?php endforeach ?>
</div>
</div><div class="clear"></div>
<?php endif ?>