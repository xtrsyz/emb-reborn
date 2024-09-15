<div class="card mb-2 <?php echo !empty($card_class) ? $card_class : '' ?>">
	<div class="card-header">
		<h5 class="card-title my-0"><?php echo $card_title ?></h5>
	</div>
	<div class="card-body py-2">
		<table data-option-table class="table table-sm font-monospace small m-0">
			<tbody>
<?php if(empty($rows)): ?>
				<tr data-option-no-rows><td class="text-center" colspan=2>No config in this segment.</td></tr>
<?php endif ?>
<?php foreach($rows as $item): ?>
				<tr>
					<td class="text-nowrap" style="width:50px"><?php echo $item[0] ?></td>
					<td><?php echo nl2br(htmlspecialchars($item[1])) ?></td>
				</tr>
<?php endforeach ?>
			</tbody>
		</table>
<?php if(!empty($card_footer)): ?>
		<div class="card-footer text-body-secondary"><?php echo $card_footer ?></div>
<?php endif ?>
	</div>
</div>
