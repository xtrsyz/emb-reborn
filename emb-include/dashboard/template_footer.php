<?php $app->action('dashboard_view_content_end') ?>

<!-- 				</div>/.container -->
<!-- 			</div>/.content -->

<!-- 			<aside class="control-sidebar control-sidebar-dark"></aside> -->
		<footer class="main-footer border-top mt-4 py-3">
			<div class="float-end">
				Load: <?php echo round((microtime(true)-$_SERVER['REQUEST_TIME_FLOAT']), 3) ?> secs
				| Memory: <?php echo Utilities::formatBytes(memory_get_peak_usage(true)) ?>
				| <b>Version</b> <?php echo $app::VERSION ?> <?php echo $config->development ? '(Dev Mode)' : '' ?>
			</div>
			<strong>Powered by EmbuhTeam.</strong>
		</footer>
		</main>
	</div><!-- /.row -->
</div><!-- /.wrapper -->
<div class="modal fade login" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-md modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white text-center">
				<h5 class="modal-title">Login</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="mb-2">
					<label class="form-label" for="modal-username">Username</label>
					<input form="modal-login-form" id="modal-username" type="text" class="form-control" name="username" placeholder="Username" autofocus>
				</div>
				<div class="mb-2">
					<label class="form-label" for="modal-password">Password</label>
					<input form="modal-login-form" id="modal-password" type="password" class="form-control" name="password" placeholder="Password">
				</div>
				<div class="form-check">
					<input class="form-check-input" form="modal-login-form" type="checkbox" id="login-keep" name="remember" disabled>
					<label class="form-check-label" for="login-keep">Keep me logged in</label>
				</div>
			</div>
			<div class="modal-footer">
				<form id="modal-login-form" action="<?php echo $content->dashboard_login_url ?>" method="POST" autocomplete="off">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary px-4">Login</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo $content->dashboard_script_js_url ?>"></script>
<script><?php echo $content->dashboard_toastr ?></script>
</body>
</html>
