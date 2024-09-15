<div class="card mb-2 <?php echo !empty($card_class) ? $card_class : '' ?>">
	<div class="card-header">
		<div class="float-end">
		<div class="input-group">
			<button data-option-button-save-all class="btn btn-sm btn-secondary text-nowrap rounded-0 py-1">Save All</button>
			<button data-option-button-save-changes class="btn btn-sm btn-primary text-nowrap rounded-0 py-1 ms-2">Save Changes</button>
		</div>
		</div>
		<h5 class="card-title m-0 mt-1"><?php echo $card_title ?></h5>
	</div>
	<div class="card-body py-2">
		<table data-option-table class="table table-sm table-borderless font-monospace small m-0">
			<tbody>
				<script data-option-rows type="application/json"><?php echo json_encode($rows) ?></script>
				<tr data-option-no-rows><td class="text-center" colspan=2>Loading ...</td></tr>
<?php if(!is_array($includes)): ?>
				<tr><td colspan=2><hr class="my-0"></td></tr>
				<tr>
					<td class="text-nowrap" style="width:50px">
						<div class="d-flex">
							<span class="mt-1"><?php echo $prefix ?></span>
							<input form="set_option_<?php echo $prefix ?>" class="form-control form-control-xs rounded-0 px-1 py-0 small" style="height:initial !important;min-width:40px" name="name" placeholder="key" required>
						</div>
					</td>
					<td class="text-nowrap">
						<form id="set_option_<?php echo $prefix ?>" data-option-form-add class="d-flex">
							<div class="input-group">
								<input class="form-control form-control-xs rounded-0 px-1 py-0 small" style="height:initial !important" name="value" placeholder="value" required>
							<input type="hidden" name="prefix" value="<?php echo $prefix ?>">
							<button type="submit" class="btn btn-sm btn-outline-primary rounded-0 ms-sm-2 py-0">Save</button>
							</div>
						</form>
					</td>
				</tr>
<?php endif ?>
			</tbody>
		</table>
<?php if(!empty($card_footer)): ?>
		<div class="card-footer text-body-secondary"><?php echo $card_footer ?></div>
<?php endif ?>
	</div>
</div>
