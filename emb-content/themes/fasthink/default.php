<?php include 'header.php' ?>
<div class="blog-posts hfeed">
	<h1 class="post-title entry-title"><?php echo htmlspecialchars($content->title) ?: 'Fasthink Theme for EmbuhEngine' ?></h1>
	<div class="date-outer">
		<div class="date-posts">
			<?php echo htmlspecialchars($content->content) ?: '<p>This is default page.</p>' ?>
		</div>
	</div>
</div>
<?php include 'footer.php' ?>