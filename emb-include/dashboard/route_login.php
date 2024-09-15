<?php $content->document_title = 'Login' ?>
<?php include 'template_header.php' ?>
<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="card card-primary">
			<div class="card-header"><h3 class="card-title">Login</h3></div>
			<form method="POST" action="<?php echo $content->dashboard_login_url ?>">
				<div class="card-body">
					<div class="form-group">
						<label for="login-username">Username</label>
						<input type="text" class="form-control" id="login-username" name="username" placeholder="Username" autofocus>
					</div>
					<div class="form-group">
						<label for="login-password">Password</label>
						<input type="password" class="form-control" id="login-password" name="password" placeholder="Password">
					</div>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="login-keep" name="remember" disabled>
						<label class="form-check-label" for="login-keep">Keep me logged in</label>
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php include 'template_footer.php'; exit; ?>
