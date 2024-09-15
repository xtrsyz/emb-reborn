<div class="card mb-2 <?php echo !empty($card_class) ? $card_class : '' ?>" id="extra_end_<?php echo !empty($card_name) ? $card_name : rand(10,100) ?>">
<?php if(!empty($card_title)): ?>
	<div class="card-header <?php echo !empty($card_header_class) ? $card_header_class : '' ?>"><h5 class="card-title my-0"><?php echo $card_title ?></h5></div>
<?php endif ?>

	<div class="card-body <?php echo !empty($card_body_class) ? $card_body_class : '' ?>">
<?php if(!empty($card_body)): echo $card_body ?>
<?php elseif(!empty($card_body_path)): $app->invoke($card_body_path) ?>
<?php elseif(!empty($card_body_md)): echo Parsedown::instance()->text($app->parse($card_body_md)) ?>
<?php elseif(!empty($card_body_md_path) && is_file($card_body_md_path)): echo Parsedown::instance()->text($app->parse(file_get_contents($card_body_md_path))) ?>
<?php elseif(!empty($card_ul)): ?><ul><?php foreach($card_ul as $list) echo "<li>$list</li>"; ?></ul>
<?php elseif(!empty($card_ol)): ?><ol><?php foreach($card_ol as $list) echo "<li>$list</li>"; ?></ol>
<?php elseif(!empty($card_table)): ?>
		<div class="table-responsive" style="/*margin: -1rem !important;*/">
		<table class="table <?php echo !empty($card_table['class']) ? $card_table['class'] : 'table-sm' ?>">
<?php if(!empty($card_table['header']) && is_array($card_table['header'])): ?>
			<thead class=""><tr><?php foreach($card_table['header'] as $th) echo "<th role=\"button\">$th</th>" ?></tr></thead>
<?php elseif(!empty($card_table['header_row_html'])): ?>
			<thead class=""><?php echo $card_table['header_row_html'] ?></thead>
<?php elseif(!empty($card_table['header_rows_html']) && is_array($card_table['header_rows_html'])): ?>
			<thead class=""><?php foreach($card_table['header_rows_html'] as $tr) echo $tr ?></thead>
<?php elseif(!empty($card_table['header_html'])): echo $card_table['header_html'] ?>
<?php endif ?>
			<tbody>
<?php if(!empty($card_table['body_rows_html']) && is_array($card_table['body_rows_html'])): foreach($card_table['body_rows_html'] as $tr) echo $tr ?>
<?php elseif(!empty($card_table['body'])): foreach($card_table['body'] as $row): ?><tr><?php foreach($row as $td) echo "<td>$td</td>" ?></tr><?php endforeach ?>
<?php endif ?>
			</tbody>
<?php if(!empty($card_table['footer'])): ?>
			<tfoot><tr><?php foreach($card_table['footer'] as $td) echo "<td>$td</td>" ?></tr></tfoot>
<?php elseif(!empty($card_table['footer_row_html'])): ?>
			<tfoot><?php echo $card_table['footer_row_html'] ?></tfoot>
<?php elseif(!empty($card_table['footer_rows_html']) && is_array($card_table['footer_rows_html'])): ?>
			<tfoot><?php foreach($card_table['footer_rows_html'] as $tr) echo $tr ?></tfoot>
<?php endif ?>
		</table>
		</div>
<?php endif ?>
	</div>

<?php if(!empty($card_footer)): ?>
	<div class="card-footer text-body-secondary"><?php echo $card_footer ?></div>
<?php endif ?>
</div>
