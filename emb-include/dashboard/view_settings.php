<ul class="nav nav-pills mb-2">
  <li class="nav-item me-1"><a data-bs-toggle="tab" data-bs-target="#general-tab-pane" class="nav-link active" href="#">General</a></li>
  <li class="nav-item me-1"><a data-bs-toggle="tab" data-bs-target="#rule-tab-pane" class="nav-link" href="#">Rules</a></li>
  <li class="nav-item me-1"><a data-bs-toggle="tab" data-bs-target="#robots-tab-pane" class="nav-link" href="#">Meta Robots</a></li>
  <li class="nav-item me-1"><a data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" class="nav-link" href="#">Dashboard</a>
  <li class="nav-item me-1"><a data-bs-toggle="tab" data-bs-target="#misc-tab-pane" class="nav-link" href="#">Misc</a>
</ul>
<div class="tab-content mb-2">
	<div class="tab-pane show active" id="general-tab-pane" role="tabpanel" tabindex="0">
<?php $dashboard->__helper_invoke_card_config_editor('site_', [], 'Site Configuration') ?>
	</div>
	<div class="tab-pane" id="rule-tab-pane" role="tabpanel" tabindex="0">
<?php $dashboard->__helper_invoke_card_config_editor('rule_', [], 'Permalink Rules') ?>
	</div>
	<div class="tab-pane" id="robots-tab-pane" role="tabpanel" tabindex="0">
<?php $dashboard->__helper_invoke_card_config_editor('meta_robots_', [], 'Meta Robots') ?>
	</div>
	<div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" tabindex="0">
<?php $dashboard->__helper_invoke_card_config_editor('misc_', [], 'Miscellaneous') ?>
	</div>
	<div class="tab-pane" id="misc-tab-pane" role="tabpanel" tabindex="0">
<?php $dashboard->__helper_invoke_card_config_editor('dashboard_', [], 'Dashboard Configuration') ?>
	</div>
</div>

<div class="card mb-2">
	<div class="card-body" id="content-body">
		<blockquote class="m-0 p-0"><strong>Hints:</strong>
			<div>Save All    : save all config in the segment into database</div>
			<div>Save Changes: save changed config only into database</div>
			<div>To Textarea : <kbd>Ctrl+Enter</kbd></div>
			<div>Save        : <kbd>Enter</kbd> / <kbd>Shift+Enter</kbd></div>
			<div>Delete      : set value = <code>null</code></div>
			<div>Cancel      : <kbd>Esc</kbd></div>
			<div>DWYOR!</div>
		</blockquote>
	</div>
</div>

<script src="<?php echo $content->dashboard_index_url ?>/script-config-editor.js?v=1.0.<?php echo (int)$params->dev ?>"></script>
