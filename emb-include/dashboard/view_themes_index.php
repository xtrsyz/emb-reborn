<?php
$glob = glob(APP_ROOT_PATH."/{$config->path_themes}/[!_]*");
$themes = [];
foreach($glob as $path) {
	$name = basename($path);
	$themes[] = $name;
}
?>
<div class="card mb-2">
	<div class="card-header"><h5 class="card-title"><?php echo $content->card_title ?: $content->page_title ?></h5></div>
	<div class="card-body">
		<ul>
<?php foreach($themes as $name): ?>
			<li><a href="<?php echo $dashboard->URI('themes/manage', $name) ?>"><?php echo $name ?></a></li>
<?php endforeach ?>
		</ul>
	</div>
</div>
