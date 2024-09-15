<?php include 'header.php' ?>

<div class="blog-posts hfeed">
<?php if($content->title): ?>
	<h1 class="post-title entry-title"><?php echo htmlspecialchars($content->title) ?></h1>
<?php endif ?>
<?php if($content->summary): ?>
	<div class="status-msg-wrap">
		<div class="status-msg-body"><?php echo $content->summary ?></div>
		<div class="status-msg-border">
			<div class="status-msg-bg">
				<div class="status-msg-hidden"><?php echo $content->summary ?></div>
			</div>
		</div>
	</div>
	<div style="clear: both;"></div>
<?php endif ?>

	<div class="date-outer">
		<div class="date-posts">
<?php foreach($content->items ?: [] as $i => $item) include 'item.php' ?>

<?php if(empty($content->items)) echo $content->noitems ?>

		</div>
	</div>

</div><!-- /.blog-posts -->
<?php include 'pager.php' ?>
<?php include 'footer.php' ?>