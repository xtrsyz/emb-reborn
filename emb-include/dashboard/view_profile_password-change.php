<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">Reset Password</div>
			<div class="card-body">
				<form id="resetPasswordForm" method="POST">
					<div class="form-group mb-2">
						<label for="oldPassword">Old Password</label>
						<div class="input-group">
							<input type="password" class="form-control" id="oldPassword" name="password_old" autocomplete="off">
							<span class="input-group-text" id="toggleOldPassword"><i class="fas fa-eye fa-fw"></i></span>
						</div>
					</div>
					<div class="form-group mb-2">
						<label for="newPassword">New Password</label>
						<div class="input-group">
							<input type="password" class="form-control" id="newPassword" name="password_new" autocomplete="off">
							<span class="input-group-text" id="toggleNewPassword"><i class="fas fa-eye fa-fw"></i></span>
						</div>
					</div>
					<div class="form-group mb-2">
						<label for="confirmNewPassword">Confirm New Password</label>
						<div class="input-group">
							<input type="password" class="form-control" id="confirmNewPassword" name="password_new_confirm" autocomplete="off">
							<span class="input-group-text" id="toggleConfirmNewPassword"><i class="fas fa-eye fa-fw"></i></span>
						</div>
					</div>
					<button type="submit" class="btn btn-primary mb-2">Submit</button>
					<button type="reset" class="btn btn-secondary mb-2">Reset</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Add custom JavaScript for password toggle functionality -->
<script>
    $(document).ready(function() {
        $("#toggleOldPassword, #toggleNewPassword, #toggleConfirmNewPassword").click(function() {
            var input = $(this).closest(".input-group").find("input");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $(this).find("i").removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                input.attr("type", "password");
                $(this).find("i").removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
    });
</script>
