<div class="card border-primary mb-2">
	<div class="card-header text-bg-primary"><h5 class="card-title my-0">Overview</h5></div>
	<div class="card-body py-2">
		<div class="table-responsive">
		<table id="table_opt" class="table table-sm font-monospace small m-0">
			<tbody>
				<tr><td class="text-nowrap">EmbuhBlog             </td><td class="w-100">v<?php echo EmbuhBlog::VERSION ?></td></tr>
				<tr><td class="text-nowrap">App.php               </td><td class="w-100">v<?php echo App::VERSION ?></td></tr>
				<tr><td class="text-nowrap">Collection.php        </td><td class="w-100">v<?php echo Collection::VERSION ?></td></tr>
				<tr><td class="text-nowrap">Widget.php            </td><td class="w-100">v<?php echo Widget::VERSION ?></td></tr>
				<tr><td class="text-nowrap">PHP version           </td><td class="w-100"><?php echo phpversion() ?></td></tr>
				<tr><td class="text-nowrap">SQLite version        </td><td><?php echo $db->col("SELECT sqlite_version()") ?></td></tr>
				<tr><td class="text-nowrap">PHP binary path       </td><td><?php echo PHP_BINARY ?></td></tr>
				<tr><td class="text-nowrap">Home path             </td><td><?php echo getcwd() ?></td></tr>
				<tr><td class="text-nowrap">Installation path     </td><td><?php echo realpath(APP_ROOT_PATH) ?></td></tr>
				<tr><td class="text-nowrap">Domain                </td><td><?php echo $app->domain ?></td></tr>
				<tr><td class="text-nowrap">Base URL              </td><td><?php echo $app->baseurl ?></td></tr>
				<tr><td class="text-nowrap">Home URL              </td><td><?php echo $app->homeurl ?></td></tr>
				<tr><td class="text-nowrap">Database Name         </td><td><?php echo $config->db_name ?></td></tr>
				<tr><td class="text-nowrap">Database Skeleton     </td><td><?php echo $config->db_skel ?></td></tr>
				<tr><td class="text-nowrap">Database size         </td><td><?php echo Utilities::formatBytes(filesize("{$app->app_root_path}/{$config->path_database}/{$config->site_db_name}")) ?></td></tr>
				<tr><td class="text-nowrap">Server load avg       </td><td><?php echo implode(' ', sys_getloadavg()) ?></td></tr>
			</tbody>
		</table>
		</div>
	</div>
</div>
<?php $dashboard->__helper_invoke_card_config_editor('path_', [], "Path", true) ?>

<script src="<?php echo $content->dashboard_script_editor_js_url ?>"></script>
