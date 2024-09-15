<?php include 'header.php' ?>
<div class="blog-posts hfeed">

<div class="date-outer">
<div class="date-posts">

<div class="post-outer">
<article class="post hentry">
<h1 class="post-title entry-title"><?php echo htmlspecialchars($content->title) ?></h1>
<div class="post-body entry-content" id="post-body">
<?php echo $content->content ?>
<div style="clear: both;"></div>
</div>
</article>
</div>

</div></div>

</div>
<?php include 'footer.php' ?>