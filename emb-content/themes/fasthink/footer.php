</div></div>
</div>
<!-- </div>
post wrapper end -->
<!-- sidebar wrapper start -->
<div id='sidebar-wrapper'>
<div class="sidebar section" id="sidebar">
<?php include 'sidebar.php' ?>
</div>
</div>
<!-- sidebar wrapper end -->
<!-- spacer for skins that want sidebar and main to be the same height-->
<div class='clear'>&#160;</div>
</div>
<!-- end content-wrapper -->
<div class='clear'></div>
<!-- footer wrapper start -->
<footer id="footer-wrap">
<div class="ads728-wrap" id="ads728-wrapper">
<div class="ads728 no-items section" id="ads728-footer"><?php echo $content->ads_728_footer ?></div>
<div class="clear"></div>
</div>
<div class="emb-content-footer">
<?php echo $content->footer ?>
<div class="clear"></div>
</div>
<div id="copyright">
<div class="footer-menu">
<div class="footer-menu no-items section" id="footer-menu"></div>
</div>
<div style="clear: both;"></div>
<!-- Do not remove the credit link below -->
<div style="float:left;">2024 &copy; <a href="<?php echo $app->homepath ?>"><?php echo $config->site_name ?: 'Embuh Blog' ?></a>. Template By Templatoid | Loaded in <?php echo round((microtime(true)-$_SERVER['REQUEST_TIME_FLOAT']), 3) ?> secs</div>
<div id="footer-socio" style="float:right;">
<a href="#"><span class="socio-twitter"></span></a>
<a href="#"><span class="socio-facebook"></span></a>
<a href="#"><span class="socio-gplus"></span></a>
<a href="#"><span class="socio-rss"></span></a>
</div>
<div style="clear: both;"></div>
</div>
</footer>
</div><!-- end-wrapper -->
</div>
</div>
<div id='totop' style='display: block;'>
<a href='#'><span id='totop-icon'></span><br/>Top</a></div>
<script type='text/javascript'>
	var jQueryscrolltotop = jQuery("#totop");
	jQueryscrolltotop.css('display', 'none');
	jQuery(function () {
		jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > 100) {
				jQueryscrolltotop.slideDown('fast');
			} else {
				jQueryscrolltotop.slideUp('fast');
			}
		});
		jQueryscrolltotop.click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, 'fast');
			return false;
		});
	});
</script>
<?php echo $content->footer_end ?>
</body>
</html>