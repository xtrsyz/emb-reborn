<div class="towo"></div>

<div class="date-outer">
<h2 class="date-header"><span><?php echo $item->date_published ?></span></h2>
<div class="date-posts">
<div class="post-outer">
<div class="post hentry">
<a name="<?php echo $item->post_id ?>"></a>
<img src="<?php echo $item->meta_thumbnail ?: 'https://2.bp.blogspot.com/-ex3V86fj4dQ/UrCQQa4cLsI/AAAAAAAAFdA/j2FCTmGOrog/s1600/no-thumbnail.png' ?>" class="post-thumbnail" alt="<?php echo htmlspecialchars($item->title) ?>">
<h2 class="post-title entry-title">
<a href="<?php echo $item->permalink ?>" title="<?php echo htmlspecialchars($item->title) ?>"><?php echo htmlspecialchars($item->title) ?></a>
</h2>
<div class="post-header">
<div class="post-header-line-1">
<div class="post-info">
<div class="post-info-icon admin">By <span class="vcard"><span class="fn"><?php echo htmlspecialchars($item->author_name) ?></span></span></div>
<div class="post-info-icon jam">On <span class="post-timestamp">
<meta content="<?php echo $item->permalink ?>" itemprop="url">
<a class="updated" href="<?php echo $item->permalink ?>" rel="bookmark" title="permanent link"><abbr class="published" itemprop="datePublished" title="<?php echo $item->publishedAt ?>"><?php echo gmdate('j M Y', $item->date_published) ?></abbr></a>
</span>
</div>
</div>
</div>
</div>
<div class="post-body entry-content" id="post-body-<?php echo $item->post_id ?>">
<div class="post-snippet">
<?php echo $item->summary ?>
</div>
<div class="jump-link pull-right"><a href="<?php echo $item->permalink ?>" title="<?php echo htmlspecialchars($item->title) ?>">View Detail</a></div>
<div style="clear: both;"></div>
</div>
<div class="post-footer">
<div class="post-footer-line post-footer-line-2" style="display:none;"></div>
<div class="post-footer-line post-footer-line-3" style="display:none;"></div>
</div>
</div>
</div>

</div>
</div>

<div class="towo2"></div>
