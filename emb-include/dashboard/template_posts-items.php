<div class="card card-primary card-outline">
	<div class="card-header">
		<div class="card-tools float-end">
			<form class="p-0 m-0">
				<div class="input-group input-group-sm">
					<input type="text" class="form-control form-control-sm" placeholder="Search Post" name="q" value="<?php echo htmlspecialchars($params->GET->q) ?>">
					<button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
				</div>
			</form>
		</div>
		<h5 class="card-title"><?php echo $content->post_card_title ?></h5>
	</div>

	<div class="card-body p-0">
		<div class="post-controls py-2 pe-2">
			<button type="button" class="btn btn-sm border-0 checkbox-toggle"><i class="far fa-square"></i></button>
			<div class="btn-group">
				<button type="button" class="btn btn-light border btn-sm" name="<?php echo $params->method == 'trash' ? 'post_multi_permanent_delete' : 'post_multi_trash' ?>"><i class="far fa-trash-alt"></i></button>
<!-- 				<button type="button" class="btn btn-light border btn-sm"><i class="fas fa-reply"></i></button> -->
<!-- 				<button type="button" class="btn btn-light border btn-sm"><i class="fas fa-share"></i></button> -->
			</div>
<!-- 			<button type="button" class="btn btn-light border btn-sm"><i class="fas fa-sync-alt"></i></button> -->

			<div class="float-end">
				<?php echo $content->posts_from.'-'.$content->posts_to.' of '.$content->posts_count ?>
				<div class="btn-group">
					<a href="<?php echo $content->prev_page ?: 'javascript:void(0)' ?>" class="btn btn-light border btn-sm"><i class="fas fa-chevron-left"></i></a>
					<a href="<?php echo $content->next_page ?: 'javascript:void(0)' ?>" class="btn btn-light border btn-sm"><i class="fas fa-chevron-right"></i></a>
				</div>
			</div>
		</div>
		<div class="table-responsive post-lists">
			<table class="table table-hover table-striped">
				<tbody>
<?php foreach($content->all_posts as $i => $item): ?>
					<tr>
						<td>
							<div class="icheck-primary"><input type="checkbox" name="ids[]" value="<?php echo $item->post_id ?>" id="check-<?php echo $i+1 ?>"><label for="check-<?php echo $i+1 ?>"></label></div>
						</td>
						<td class="post-name w-100">
							<?php echo $item->status == 'Draft' ? '[Draft] ' : '' ?><a href="<?php echo $item->post_edit_url ?>"><?php echo htmlspecialchars($item->title) ?></a>
<?php if($params->route != 'pages'): ?>
							<div>Category: <?php echo htmlspecialchars(implode(', ', array_column($item->categories, 'title'))) ?></div>
<?php endif ?>
						</td>
						<td class="text-right text-nowrap">
<?php if($item->status == 'Published'): ?>
							<a href="<?php echo $item->permalink ?>" target="emb_view" class="btn btn-link m-0 p-0 pr-1"><i class="fas fa-eye"></i> View</a>
							<form class="d-inline" method="POST">
								<input type="hidden" name="id" value="<?php echo $item->post_id ?>">
								<input type="hidden" name="method" value="draft">
								<button type="submit" class="btn btn-link m-0 p-0"><i class="fas fa-share-square fa-rotate-180"></i> Unpublish</button>
							</form>
							<span class="d-block"><?php echo gmdate('d M Y H:i', $item->date_published) ?></span>
<?php elseif($item->status == 'Scheduled'): ?>
							<form method="POST">
								<input type="hidden" name="id" value="<?php echo $item->post_id ?>">
								<input type="hidden" name="method" value="publish">
								<button type="submit" class="btn btn-link m-0 p-0"><i class="fas fa-paper-plane"></i> Publish Now</button>
							</form>
							<span class="d-block"><?php echo gmdate('d M Y H:i', $item->date_published) ?></span>
<?php elseif($item->status == 'Draft'): ?>
							<form method="POST">
								<input type="hidden" name="id" value="<?php echo $item->post_id ?>">
								<input type="hidden" name="method" value="publish">
								<button type="submit" class="btn btn-link m-0 p-0"><i class="fas fa-paper-plane"></i> Publish</button>
							</form>
<?php elseif($item->status == 'Trash'): ?>
							<form method="POST">
								<input type="hidden" name="id" value="<?php echo $item->post_id ?>">
								<input type="hidden" name="method" value="publish">
								<button type="submit" class="btn btn-link m-0 p-0"><i class="fas fa-trash-restore"></i> Restore</button>
							</form>
							<form method="POST">
								<input type="hidden" name="id" value="<?php echo $item->post_id ?>">
								<input type="hidden" name="method" value="draft">
								<button type="submit" class="btn btn-link m-0 p-0"><i class="fas fa-trash-restore"></i> Restore as Draft</button>
							</form>
<?php endif ?>
						</td>
					</tr>
<?php endforeach ?>
<?php if(!$content->all_posts): ?>
					<td class="text-center p-3">No item to show</td>
<?php endif ?>
				</tbody>
			</table>

		</div>

	</div>

	<div class="card-footer p-0">
		<div class="post-controls py-2 pe-2">
			<button type="button" class="btn btn-sm border-0 checkbox-toggle"><i class="far fa-square"></i></button>
			<div class="btn-group">
				<button type="button" class="btn btn-light border btn-sm" name="<?php echo $params->method == 'trash' ? 'post_multi_permanent_delete' : 'post_multi_trash' ?>"><i class="far fa-trash-alt"></i></button>
<!-- 				<button type="button" class="btn btn-light border btn-sm"><i class="fas fa-reply"></i></button> -->
<!-- 				<button type="button" class="btn btn-light border btn-sm"><i class="fas fa-share"></i></button> -->
			</div>
<!-- 			<button type="button" class="btn btn-light border btn-sm"><i class="fas fa-sync-alt"></i></button> -->

			<div class="float-end">
				<?php echo $content->posts_from.'-'.$content->posts_to.' of '.$content->posts_count ?>
				<div class="btn-group">
					<a href="<?php echo $content->prev_page ?: 'javascript:void(0)' ?>" class="btn btn-light border btn-sm"><i class="fas fa-chevron-left"></i></a>
					<a href="<?php echo $content->next_page ?: 'javascript:void(0)' ?>" class="btn btn-light border btn-sm"><i class="fas fa-chevron-right"></i></a>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function() {
	$.extend({
		doGet: function(url, params) {
			document.location = url + '?' + $.param(params);
		},
		doPost: function(url, params) {
			var $form = $("<form method='POST'>").attr("action", url);
			$.each(params, function(name, value) {
			console.log(value)
			console.log(typeof value)
				if(typeof value == 'object')
					value.forEach(item => $("<input type='hidden'>").attr("name", name+'[]').attr("value", item).appendTo($form))
				else
					$("<input type='hidden'>").attr("name", name).attr("value", value).appendTo($form);
			});
			$form.appendTo("body");
			$form.submit();
		}
	});
	$('button[name=post_multi_trash]').on('click', function() {
		if(!confirm('This will move selected posts to trash, are you sure?'))
			return;
		var ids = [];
		$('input[name^=ids]:checked').each((i, el) => ids.push(el.value));
		$.doPost(location.href, {route: 'post', method: 'trash', ids});
	});
	$('button[name=post_multi_permanent_delete]').on('click', function() {
		if(!confirm('This will delete selected posts permanently, are you sure?'))
			return;
		var ids = [];
		$('input[name^=ids]:checked').each((i, el) => ids.push(el.value));
		$.doPost(location.href, {route: 'post', method: 'trash', permanent: 1, ids});
	});
	$('.checkbox-toggle').click(function () {
		var clicks = $(this).data('clicks')
		if (clicks) {
			//Uncheck all checkboxes
			$('input[name^=ids]').prop('checked', false)
			$('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
		} else {
			//Check all checkboxes
			$('input[name^=ids]').prop('checked', true)
			$('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
		}
		$(this).data('clicks', !clicks)
	})
});
</script>
