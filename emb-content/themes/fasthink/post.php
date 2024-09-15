<?php include 'header.php' ?>
<div class="blog-posts hfeed">
          <div class="date-outer">
<h2 class="date-header"><span><?php echo $content->date_published ?></span></h2>
          <div class="date-posts">
<div class="post-outer">
<div class="post hentry">
<a name="<?php echo $content->post_id ?>"></a>
<h1 class="post-title entry-title">
<a href="<?php echo $app->urlpath ?>" title="<?php echo htmlspecialchars($content->title) ?>"><?php echo htmlspecialchars($content->title) ?></a>
</h1>
<div class="post-header">
<div class="post-header-line-1">
<div class="post-info">
<div class="post-info-icon admin">By <span class="vcard"><span class="fn"><?php echo $content->author_name ?></span></span></div>
<div class="post-info-icon jam">On <span class="post-timestamp">
<meta content="<?php echo $content->permalink ?>" itemprop="url">
<a class="updated" href="<?php echo $content->permalink ?>" rel="bookmark" title="permanent link"><abbr class="published" itemprop="datePublished" title="<?php echo $content->publishedAt ?>"><?php echo gmdate('j M Y', $content->date_published) ?></abbr></a>
</span>
</div>
</div>
</div>
</div>
<div class="ads-slot1"></div>
<div class="post-body entry-content" id="post-body-<?php echo $content->post_id ?>">
<div class="iklanbawahjudul"><?php echo $content->ads_below_title ?></div>
<?php echo $content->content_before ?>
<?php echo $content->content ?>
<?php echo $content->content_after ?>
<div style="clear: both;"></div>
</div>
<div class="post-footer">
<div class="post-footer-line post-footer-line-1">
<div style="padding: 10px 0;">
<?php if($items = $widget->getContent('post_footer', 'tags')): ?>
Tags:
<?php foreach($items as $item): ?>
	<a class="label-block" href="<?php echo $item['href'] ?>" rel="tag" style="margin-left: 10px;">#<?php echo htmlspecialchars($item['text']) ?></a>
<?php endforeach ?>
<?php endif ?>
</div>
<div class="ads-slot2"><?php echo $content->ads_below_post ?></div>
<?php include 'related.php' ?>
</div>
<div class="post-footer-line post-footer-line-2" style="display:none;"></div>
<div class="post-footer-line post-footer-line-3" style="display:none;"></div>
</div>
</div>
<div class="comments" id="comments"></div>
</div>
</div></div>
</div>
<div class="clear"></div>
<div class="post-feeds">
</div>

<?php include 'footer.php' ?>