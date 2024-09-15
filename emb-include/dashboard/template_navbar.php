<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
	<div class="<?php echo $content->dashboard_container_class ?> p-0">
		<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-bg-dark" href="<?php echo $content->dashboard_index_url ?>">
			<span class="brand-image me-2" style="/*opacity:.8;*/width:33px;height:33px;"><i class="fas fa-<?php echo $config->dashboard_brand_image_icon ?: 'coffee' ?> fa-fw"></i></span><?php echo $config->site_name ?>
		</a>
		<ul class="navbar-nav flex-row d-md-none me-auto">
			<li class="nav-item text-nowrap">
				<button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation"><i class="fas fa-bars"></i></button>
			</li>
		</ul>
		<div class="navbar-expand col-md-9 col-lg-10 px-3">
			<div class="collapse navbar-collapse">
				<ul class="navbar-nav">
<?php foreach($widget->getContent('dashboard', 'navbar_collapse') as $item): ?>
					<li class="nav-item"><a class="nav-link px-2" href="<?php echo $item['href'] ?>"><?php echo $item['text'] ?></a></li>
<?php endforeach ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-plus-square"></i> Add</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="<?php echo $dashboard->URI('posts/create') ?>">Post</a></li>
							<li><a class="dropdown-item" href="<?php echo $dashboard->URI('pages/create') ?>">Page</a></li>
						</ul>
					</li>
				</ul>
				<ul class="navbar-nav ms-auto">
					<li class="nav-item"><a class="nav-link px-2" href="javascript:void(0)" rel="noopener noreferrer" role="button" title="Toggle Theme" data-bs-theme-toggle=""><i class="fas fa-adjust"></i></a></li>
					<li class="nav-item d-none d-sm-inline-block"><a class="nav-link px-2" href="<?php echo $app->homepath ?>" target="_blank" rel="noopener noreferrer" role="button" title="Visit Homepage"><i class="fas fa-home"></i></a></li>
					<li class="nav-item"><a class="nav-link px-2 button-logout" href="<?php echo $content->dashboard_logout_url ?>" role="button" title="Logout"><i class="fas fa-sign-out-alt"></i></a></li>
				</ul>
			</div>
		</div>

		<div id="navbarSearch" class="navbar-search w-100 collapse">
			<input class="form-control w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
		</div>
	</div>
</header>
