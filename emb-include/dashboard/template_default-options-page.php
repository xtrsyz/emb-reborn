<!-- card_extra_start -->
<div class="row" id="card_extra_start">
<?php foreach($content->card_extra_start ?: [] as $name => $card): extract($card); ?>
	<div class="<?php echo !empty($col) ? $col : 'col' ?>">
<?php $dashboard->__helper_invoke_card($card) ?>
	</div>
<?php endforeach ?>
</div><!-- /#card_extra_start -->

<?php if(!$content->card_config_editor_hide) $dashboard->__helper_invoke_card_config_editor($params->target.'_') ?>

<!-- card_extra_end -->
<div class="row" id="card_extra_end">
<?php foreach($content->card_extra_end ?: [] as $name => $card): ?>
	<div class="<?php echo !empty($card['col']) ? $card['col'] : 'col' ?>">
<?php $dashboard->__helper_invoke_card($card) ?>
	</div>
<?php endforeach ?>
</div><!-- /#card_extra_end -->
