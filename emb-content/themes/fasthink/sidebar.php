<?php foreach($widget->sidebar ?: [] as $name => $widget): ?>

<div class="widget sidebar-<?php echo $name ?>" id="sidebar-<?php echo $name ?>">
<?php if(!empty($widget['title'])): ?>
	<h2><?php echo $widget['title'] ?></h2>
<?php endif ?>
	<div class="widget-content">
<?php if(is_array($widget['content'])): ?>
		<ul>
<?php foreach($widget['content'] as $list): ?>
			<li><a href="<?php echo $list['href'] ?>" title="<?php echo htmlspecialchars($list['text']) ?>"><?php echo htmlspecialchars($list['text']) ?></a></li>
<?php endforeach ?>
		</ul>
<?php else: ?>
			<?php echo $widget['content'] ?>

<?php endif ?>
		<div class="clear"></div>
	</div><!-- /.widget-content -->
</div><!-- /.widget -->
<?php endforeach ?>

