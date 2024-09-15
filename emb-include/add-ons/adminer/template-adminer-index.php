<div class="table-responsive p-0">
	<table class="table table-sm font-monospace small text-nowrap m-0">
		<thead>
			<tr>
				<th role="button" class="bg-primary text-white w-100">Database (<?php echo $config->path_database ?>/*.*)</th>
				<th role="button" class="bg-primary text-white text-center">Size</th>
				<th role="button" class="bg-primary text-white text-center">Last Modified</th>
				<th role="button" class="bg-primary text-white text-center">Action</th>
			</tr>
		</thead>
		<tbody>
<?php foreach(glob("{$config->path_database}/*.*") as $path): ?>
<?php $db_name = basename($path) ?>
<?php $adminer = $dashboard->URI('addons/manage/adminer/sqlite').'?db='.urlencode(("{$config->path_database}/".$db_name)) ?>
<?php if($db_name == $config->db_skel): ?>
			<tr class="bg-info-subtle">
				<td class=""><a href="<?php echo $adminer ?>" target="adminer"><?php echo $db_name ?></a></td>
				<td class=""><?php echo Utilities::formatBytes(filesize($path)) ?></td>
				<td class=""><?php echo gmdate('Y-m-d H:i:s', filemtime($path)) ?></td>
				<td class="text-center">Skeleton</td>
			</tr>
<?php elseif($db_name == $config->db_name): ?>
			<tr class="bg-info-subtle">
				<td class="fw-bold bg-info-subtle"><a href="<?php echo $adminer ?>" target="adminer"><?php echo $db_name ?></a></td>
				<td class=" bg-info-subtle"><?php echo Utilities::formatBytes(filesize($path)) ?></td>
				<td class=" bg-info-subtle"><?php echo gmdate('Y-m-d H:i:s', filemtime($path)) ?></td>
				<td class="text-center bg-info-subtle">Default DB</td>
			</tr>
<?php else: ?>
			<tr>
				<td class=""><a href="<?php echo $adminer ?>" target="adminer"><?php echo $db_name ?></a></td>
				<td class=""><?php echo Utilities::formatBytes(filesize($path)) ?></td>
				<td class=""><?php echo gmdate('Y-m-d H:i:s', filemtime($path)) ?></td>
				<td class="text-center">-</td>
			</tr>
<?php endif ?>
<?php endforeach ?>
		</tbody>
	</table>
</div>
