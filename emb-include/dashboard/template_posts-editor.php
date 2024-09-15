<div class="card card-primary card-outline">
	<div class="card-header">
		<div class="card-tools float-end" style="margin-top: -4px;">
<?php if($params->target): ?>
			<a href="<?php echo $content->post_editor_permalink ?>" target="emb_view" title="View <?php echo htmlspecialchars($content->post_editor_title) ?>" class="btn btn-tool"><i class="fas fa-external-link-alt"></i></a>
			<form action="<?php echo $dashboard->URI('posts', 'trash') ?>" method="POST" class="d-inline-block">
				<input type="hidden" name="id" value="<?php echo $params->target ?>">
				<button type="submit" class="btn btn-tool text-danger" title="Move Post to Trash" onclick="return confirm('This will move post to trash? Are you sure?')"><i class="fas fa-trash"></i></button>
			</form>
<?php endif ?>
			<button type="reset" form="form_post_editor" title="Reset" class="btn btn-tool"><i class="fas fa-sync-alt"></i></button>
		</div>
		<h5 class="card-title"><?php echo $content->post_card_title ?></h5>
	</div>
	<form id="form_post_editor" class="form-horizontal" method="POST" action="<?php echo $dashboard->URI($params->route, $params->method, $params->target) ?>">
		<div class="card-body">
			<div class="row">
				<div class="col-md-7">
					<input name="title" value="<?php echo htmlspecialchars($content->post_editor_title) ?>" type="text" class="form-control mb-2" id="post_title" placeholder="Title">
					<textarea name="content" class="form-control mb-2" style="min-height:calc(100vh - 400px)" rows="10" placeholder="Post content"><?php echo htmlspecialchars($content->post_editor_content) ?></textarea>
				</div>
				<div class="col-md-5">
					<input name="slug" value="<?php echo htmlspecialchars($content->post_editor_slug) ?>" type="text" class="form-control mb-2" id="post_slug" placeholder="Slug">
					<div class="form-group">
						<label for="post_category">Category</label>
						<input name="categories" value="<?php echo htmlspecialchars($content->post_editor_categories) ?>" type="text" class="form-control" id="post_category" placeholder="Category">
					</div>
					<div class="form-group">
						<label for="post_tag">Tags</label>
						<input name="tags" value="<?php echo htmlspecialchars($content->post_editor_tags) ?>" type="text" class="form-control" id="post_tag" placeholder="Tags">
					</div>
					<div class="form-group">
						<label>Summary</label>
						<textarea name="summary" class="form-control" rows="1" placeholder="Post Summary"><?php echo htmlspecialchars($content->post_editor_summary) ?></textarea>
					</div>
					<div class="form-group">
						<label for="post_thumbnail">Thumbnail</label>
						<input name="meta_thumbnail" value="<?php echo htmlspecialchars($content->post_editor_meta_thumbnail) ?>" type="text" class="form-control" id="post_thumbnail" placeholder="Thumbnail image URL">
					</div>

					<div class="form-group">
						<label for="post_datetime_published">Date Published</label>
						<input name="datetime_published" value="<?php echo $content->post_editor_datetime_published ?>" type="datetime-local" class="form-control" id="post_datetime_published">
					</div>
<!-- 	  <div class="form-group">
	   <label>Images</label>
	   <textarea name="meta[images]" class="form-control" rows="1" placeholder="Image URLs"><?php echo htmlspecialchars($content->post_editor_summary) ?></textarea>
	  </div> -->
				</div>
			</div>
		</div>

		<div class="card-footer">
			<input type="submit" class="btn btn-primary" value="Save">
<!-- 			<div class="form-group"> -->
				<div class="form-check form-switch float-end my-1">
					<input type="checkbox" class="form-check-input" name="draft" value="1" id="chk_draft_create" <?php echo $content->post_editor_published === 0 ? 'checked' : '' ?>>
					<label class="form-check-label" for="chk_draft_create">Draft</label>
				</div>
<!-- 			</div> -->
		</div>

	</form>
</div>
