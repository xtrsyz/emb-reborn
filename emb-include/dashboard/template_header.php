<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php echo $content->document_title ?> - <?php echo $config->site_name ?></title>
	<meta name="robots" content="noindex,nofollow">
<?php if($config->dashboard_favicon): ?>
	<link rel="shortcut icon" href="<?php echo $config->dashboard_favicon ?>">
<?php endif ?>
	<link  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
	<link  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
	<link  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
	<link  href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<meta name="theme-color" content="#0d6efd">
    <style>
.form-control:focus {
	box-shadow: initial !important;
}
.container {
	max-width: 1400px;
	width: 100%;
}
.card .overlay {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(255, 255, 255, 0.7);
	display: none;
	justify-content: center;
	align-items: center;
	z-index: 1000;
}
.bd-placeholder-img {
	font-size: 1.125rem;
	text-anchor: middle;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
}

@media (min-width: 768px) {
	.bd-placeholder-img-lg {
		font-size: 3.5rem;
	}
}
.b-example-divider {
	width: 100%;
	height: 3rem;
	background-color: rgba(0, 0, 0, .1);
	border: solid rgba(0, 0, 0, .15);
	border-width: 1px 0;
	box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
}
.b-example-vr {
	flex-shrink: 0;
	width: 1.5rem;
	height: 100vh;
}
.bi {
	vertical-align: -.125em;
	fill: currentColor;
}
.nav-scroller {
	position: relative;
	z-index: 2;
	height: 2.75rem;
	overflow-y: hidden;
}
.nav-scroller .nav {
	display: flex;
	flex-wrap: nowrap;
	padding-bottom: 1rem;
	margin-top: -1px;
	overflow-x: auto;
	text-align: center;
	white-space: nowrap;
	-webkit-overflow-scrolling: touch;
}
.btn-bd-primary {
	--bd-violet-bg: #712cf9;
	--bd-violet-rgb: 112.520718, 44.062154, 249.437846;

	--bs-btn-font-weight: 600;
	--bs-btn-color: var(--bs-white);
	--bs-btn-bg: var(--bd-violet-bg);
	--bs-btn-border-color: var(--bd-violet-bg);
	--bs-btn-hover-color: var(--bs-white);
	--bs-btn-hover-bg: #6528e0;
	--bs-btn-hover-border-color: #6528e0;
	--bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
	--bs-btn-active-color: var(--bs-btn-hover-color);
	--bs-btn-active-bg: #5a23c8;
	--bs-btn-active-border-color: #5a23c8;
}
.bd-mode-toggle {
	z-index: 1500;
}
.bd-mode-toggle .dropdown-menu .active .bi {
	display: block !important;
}
.bi {
	display: inline-block;
	width: 1rem;
	height: 1rem;
}
/*
* Sidebar
*/
@media (min-width: 768px) {
	.sidebar .offcanvas-lg {
		position: -webkit-sticky;
		position: sticky;
		top: 48px;
	}
	.navbar-search {
		display: block;
	}
}
.sidebar .nav-link {
	font-size: .875rem;
	font-weight: 500;
}
.sidebar .nav-link.active {
	color: #2470dc;
}
.sidebar-heading {
	font-size: .75rem;
}
/*
* Navbar
*/
.navbar-brand {
	padding-top: .75rem;
	padding-bottom: .75rem;
	background-color: rgba(0, 0, 0, .25);
	box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
}
.navbar .form-control {
	padding: .75rem 1rem;
}
.toast-top-right{top:64px;}
    </style>
	<script>
		const DASHBOARD_INDEX_URL = '<?php echo $content->dashboard_index_url ?>';
	</script>
</head>
<body>
<?php include 'template_navbar.php' ?>

<div class="<?php echo $content->dashboard_container_class ?> wrapper">
	<div class="row">
<?php include 'template_sidebar.php' ?>
		<main class="col-md-9 col-lg-10 ms-sm-auto px-md-4">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h3 mb-0"><?php echo $content->page_title ?></h1>
				<div class="btn-toolbar pt-1">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb mb-0">
<?php foreach($widget->getContent('dashboard', 'breadcrumb') as $item): ?>
<?php if(is_array($item)): ?>
							<li class="breadcrumb-item"><a class="text-decoration-none" href="<?php echo $item['href'] ?>"><?php echo $item['text'] ?></a></li>
<?php else: ?>
							<li class="breadcrumb-item active"><?php echo $item ?></li>
<?php endif ?>
<?php endforeach ?>
						</ol>
					</nav>
				</div>
			</div>

<?php $app->action('dashboard_view_content_top') ?>
