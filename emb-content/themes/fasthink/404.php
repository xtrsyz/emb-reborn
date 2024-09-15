<?php include 'header.php' ?>
<div class="blog-posts hfeed">
	<h1 class="post-title entry-title"><?php echo htmlspecialchars($content->title('Page Not Available')) ?></h1>
	<div class="date-outer">
		<div class="date-posts">
			<div class="separator" style="clear: both; text-align: center;">
				<img src=" data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJAAAACQBAMAAAAVaP+LAAAAGFBMVEUAAABTU1NNTU1TU1NPT09SUlJSUlJTU1O8B7DEAAAAB3RSTlMAoArVKvVgBuEdKgAAAJ1JREFUeF7t1TEOwyAMQNG0Q6/UE+RMXD9d/tC6womIFSL9P+MnAYOXeTIzMzMzMzMzaz8J9Ri6HoITmuHXhISE8nEh9yxDh55aCEUoTGbbQwjqHwIkRAEiIaG0+0AA9VBMaE89Rogeoww936MQrWdBr4GN/z0IAdQ6nQ/FIpRXDwHcA+JIJcQowQAlFUA0MfQpXLlVQfkzR4igS6ENjknm/wiaGhsAAAAASUVORK5CYII=" alt="404 Not Found" title="404 Not Found" border="0">
			</div>
			<?php echo $content->content('<p>Page Not Found. The item you are looking for probably was deleted from the original server, or it was eaten by T-Rex.</p>') ?>
		</div>
	</div>
</div>
<?php include 'footer.php' ?>