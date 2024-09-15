<?php

// getPostsCount
$content->append($dashboard->getPostsCount());
// $dashboard->buildSidebarMenu();
?>
<div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
	<div class="offcanvas-md offcanvas-start bg-body-tertiary" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
		<div class="offcanvas-header">
			<h5 class="offcanvas-title" id="sidebarMenuLabel">EmbuhBlog</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
			<ul class="nav flex-column mb-auto">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo $content->dashboard_index_url ?>">
						<i class="fas fa-tachometer-alt fa-fw"></i> Dashboard
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo $app->homepath ?>" target="_blank" title="Visit Blog Homepage">
						<i class="fas fa-home fa-fw"></i> Visit Blog
					</a>
				</li>
			</ul>

<?php foreach($widget->dashboard as $name => $value): ?>
<?php if(substr($name, 0, 8) == 'sidebar_' && !empty($value['content'])): ?>
			<hr class="my-1">
<?php if(!empty($value['title'])): ?>
			<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-2 mb-2 text-body-secondary text-uppercase">
				<span><?php echo $value['title'] ?></span>
<?php if(!empty($value['link'])): ?>
				<a class="link-secondary" href="<?php echo $value['link'] ?>"><i class="fas fa-plus fa-fw"></i></a>
<?php endif ?>
			</h6>
<?php endif ?>
			<ul class="nav flex-column mb-auto" data-sidebar-dashboard="<?php echo $name ?>">
<?php foreach($value['content'] as $item) include 'template_sidebar-list.php' ?>
			</ul>
<?php endif ?>
<?php endforeach ?>
		</div>
	</div>
</div>
