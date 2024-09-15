<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header text-bg-primary">
				<h5 class="card-title">Bulk Post</h5>
			</div>
			<div class="card-body">
				<form id="form_post_bulk" class="form-horizontal" action="<?php echo $dashboard->URI('posts/bulk') ?>" method="POST">
					<textarea name="json" class="form-control mb-2" rows="8" placeholder="JSON Data"><?php echo htmlspecialchars($params->POST->json) ?></textarea>
					<label class="form-label">Schedule</label>
					<div class="row form-group mb-0">
						<div class="col-sm-8 mb-2">
							<select class="form-select" name="schedule_from">
								<option value="">No Schedule</option>
								<option value="schedule_from_newest">Schedule from newest pub date</option>
								<option value="schedule_from_now">Schedule from now</option>
								<option value="backdate_from_oldest">Backdate from oldest pub date</option>
								<option value="backdate_from_now">Backdate from now</option>
							</select>
						</div>
						<div class="col-sm-2 mb-2">
							<input name="schedule_min" value="<?php echo $params->POST->schedule_min ?>" type="text" class="form-control mb-2" placeholder="Min (minutes)" title="Minimum interval (minutes)">
						</div>
						<div class="col-sm-2 mb-2">
							<input name="schedule_max" value="<?php echo $params->POST->schedule_max ?>" type="text" class="form-control mb-2" placeholder="Max (minutes)" title="Maximum interval (minutes)">
						</div>
					</div>
					<div class="row">
						<div class="col-4 form-group mb-0">
							<div class="form-check form-switch">
								<input type="checkbox" class="form-check-input" name="draft" value="1" id="chk_draft_create">
								<label class="form-check-label user-select-none" for="chk_draft_create">Draft</label>
							</div>
						</div>
						<div class="col-8 form-group mb-0">
							<div class="custom-control custom-switch float-end">
								<input type="checkbox" class="form-check-input" name="update_on_duplicate" value="1" id="chk_update_on_duplicate">
								<label class="form-check-label user-select-none" for="chk_update_on_duplicate">Update on Duplicate</label>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-footer">
				<input type="submit" form="form_post_bulk" class="btn btn-primary" value="Submit">
				<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-preview">Preview</button>
				<button type="button" data-clear-target="[name=json]" class="btn btn-light float-end">Clear</button>
			</div>
			<div class="overlay" style="display:none"><div class="position-absolute top-50 start-50 translate-middle"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div></div>
		</div>
	</div>

	<!-- GPT Tool Box -->
	<div class="col-md-6">
		<div class="card" id="gpt_tool_box">
			<div class="card-header text-bg-primary">
				<button class="btn border-0 text-white float-end" name="bulk_gpt_lock" style="margin-top:-4px;"><i class="fas fa-unlock"></i></button>
				<h5 class="card-title">GPT Tool Box</h5>
			</div>
			<div class="card-body">
				<div class="d-flex mb-2">
					<select class="form-select w-100" name="gpt_session" title="Select ChatGPT session">
						<option>Select GPT Session Tab</option>
					</select>
					<button type="button" class="btn btn-light ms-1 text-nowrap" name="gpt_session_visit" title="Open ChatGPT session tab">Go to Tab</button>
					<button type="button" class="btn btn-light ms-1" name="gpt_session_get" title="Refresh active session"><i class="fas fa-sync"></i></button>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="mb-2">
							<form class="position-relative" name="gpt_send_prompt">
								<button class="btn btn-sm border-0 position-absolute top-0 end-0" type="button" data-clear-target="[name=prompt1]" title="Clear"><i class="fas fa-times"></i></button>
								<textarea class="form-control mb-1" rows="1" name="prompt1" placeholder="Prompt 1"><?php echo htmlspecialchars($config->dashboard_prompt1) ?></textarea>
								<button type="submit" class="btn btn-primary w-100" data-hotkey="1" name="gpt_send_prompt" data-target="1" title="Send Prompt 1">SEND PROMPT 1</button>
							</form>
						</div>
					</div>
					<div class="col-6">
						<div class="mb-2">
							<form class="position-relative" name="gpt_send_prompt">
								<button class="btn btn-sm border-0 position-absolute top-0 end-0" type="button" data-clear-target="[name=prompt2]" title="Clear"><i class="fas fa-times"></i></button>
								<textarea class="form-control mb-1" rows="1" name="prompt2" placeholder="Prompt 2"><?php echo htmlspecialchars($config->dashboard_prompt2) ?></textarea>
								<button type="submit" class="btn btn-primary w-100" data-hotkey="2" name="gpt_send_prompt" data-target="2" title="Send Prompt 2">SEND PROMPT 2</button>
							</form>
						</div>
					</div>
					<div class="col-6">
						<div class="mb-2">
							<form class="position-relative" name="gpt_send_prompt">
								<button class="btn btn-sm border-0 position-absolute top-0 end-0" type="button" data-clear-target="[name=prompt3]" title="Clear"><i class="fas fa-times"></i></button>
								<textarea class="form-control mb-1" rows="1" name="prompt3" placeholder="Prompt 3"><?php echo htmlspecialchars($config->dashboard_prompt3) ?></textarea>
								<button type="submit" class="btn btn-primary w-100" data-hotkey="3" name="gpt_send_prompt" data-target="3" title="Send Prompt 3">SEND PROMPT 3</button>
							</form>
						</div>
					</div>
					<div class="col-6">
						<div class="mb-2">
							<form class="position-relative" name="gpt_send_prompt">
								<button class="btn btn-sm border-0 position-absolute top-0 end-0" type="button" data-clear-target="[name=prompt4]" title="Clear"><i class="fas fa-times"></i></button>
								<textarea class="form-control mb-1" rows="1" name="prompt4" placeholder="Prompt 4"><?php echo htmlspecialchars($config->dashboard_prompt4) ?></textarea>
								<button type="submit" class="btn btn-primary w-100" data-hotkey="4" name="gpt_send_prompt" data-target="4" title="Send Prompt 4">SEND PROMPT 4</button>
							</form>
						</div>
					</div>
				</div>
				<div class="mt-2">
					<div class="form-check form-check-inline">
						<input type="radio" id="customRadioInline1" name="gpt_response_auto" value="" class="form-check-input" checked>
						<label class="form-check-label user-select-none" for="customRadioInline1">No Auto</label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" id="customRadioInline2" name="gpt_response_auto" value="preview" class="form-check-input">
						<label class="form-check-label user-select-none" for="customRadioInline2">Auto Preview</label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" id="customRadioInline3" name="gpt_response_auto" value="submit" class="form-check-input">
						<label class="form-check-label user-select-none" for="customRadioInline3">Auto Submit</label>
					</div>
				</div>
				<style>#progress:before{content:">";margin-right:4px;}</style>
				<pre id="progress" class="m-0 mt-3 p-0 overflow-hidden">Ready</pre>
			</div>
			<div class="card-footer">
				<form class="d-flex" name="gpt_send_chatbox">
					<input class="form-control me-1" type="text" name="gpt_prompt" placeholder="Send a message" autocomplete="off">
					<button type="submit" class="btn btn-success me-1" title="Send message"><i class="fas fa-paper-plane"></i></button>
					<button type="button" class="btn btn-success me-1" data-hotkey="r" name="regenerate" title="Regenerate (R)"><i class="fas fa-sync"></i></button>
					<button type="button" class="btn btn-primary me-1" data-hotkey="f" name="fetch_response" title="Fetch Response (F)"><i class="fas fa-exchange-alt"></i></button>
					<button type="button" class="btn btn-secondary" data-hotkey="n" name="new_chat" title="New Chat (N)"><i class="fas fa-plus"></i></button>
				</form>
			</div>
		</div>
	</div>

</div>
<style>.modal.preview img {width: 50%;}</style>
<div class="modal fade preview" id="modal-preview" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalScrollableTitle">Preview</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary ms-auto" data-click-target="[name=gpt_send_prompt][data-target=1]" data-dismiss="modal">&gt; 1</button>
				<button type="button" class="btn btn-primary ms-1" data-click-target="[name=gpt_send_prompt][data-target=2]" data-dismiss="modal">&gt; 2</button>
				<!-- 					<button type="button" class="btn btn-primary ms-1" data-click-target="[name=new_chat]"><i class="fas fa-plus"></i></button> -->
				<button type="button" class="btn btn-primary ms-1" data-click-target="[name=regenerate]" data-dismiss="modal"><i class="fas fa-sync"></i></button>
				<button type="button" class="btn btn-primary ms-1" data-submit-target="#form_post_bulk" data-dismiss="modal">Submit</button>
				<button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
var app = {
	init: function() {
		$.ajaxSetup({
			cache: false,
			dataType: 'json',
			error: errorHandler,
			beforeSend: app.beforeSendHandler,
			complete: app.completeHandler,
			success: app.successHandler,
		});
		$(document).keydown(function(event) {
			const activeElement = document.activeElement;
			if ((activeElement.tagName === 'INPUT' && activeElement.type != 'radio' && activeElement.type != 'checkbox' && activeElement.type != 'submit') || activeElement.tagName === 'TEXTAREA' || activeElement.tagName === 'SELECT')
				return; // Skip the event handling if the active element is an input field
			if (event.ctrlKey || event.shiftKey || event.altKey)
				return; // Skip the event handling if any of these modifier keys are pressed
			if(!event.key.match(/^[0-9a-z]+$/i))
				return;
			if(!$('[data-hotkey='+event.key+']').length)
				return;
			$('[data-hotkey='+event.key+']').click();
		});
		$('form[name=gpt_send_prompt]').on('submit', function(e) {
			e.preventDefault();
			$(this).find('button[name=gpt_send_prompt]').click();
		});
		$('form[name=gpt_send_chatbox]').on('submit', function(e) {
			e.preventDefault();
		});
		$('button[data-click-target]').on('click', function(e) {
			$($(this).data('click-target')).first().trigger('click');
		});
		$('button[data-submit-target]').on('click', function(e) {
			$($(this).data('submit-target')).first().trigger('submit');
		});
		$('button[data-clear-target]').on('click', function(e) {
			$($(this).data('clear-target')).val('').focus();
		});
		$('button[name=bulk_gpt_lock]').on('click', function(e) {
			return $(this).data('is-locked') ? window.panelUnlock() : window.panelLock();
		});
		window.gptResponseHandler = function(response) {
			$('[name=json]').val(response);
			let auto = $('[name=gpt_response_auto]:checked').val();
			if(auto == 'submit')
				$('form#form_post_bulk').submit();
			else if(auto == 'preview')
				$('#modal-preview').modal('show');
			else
				$('input[type=submit][form=form_post_bulk]').focus();
		}
		window.panelSetProgress = message => $('#progress').text(message);
		window.panelLock = () => {
			$('button[name=bulk_gpt_lock]').data('is-locked', true).find('i').addClass('fa-lock').removeClass('fa-unlock');
			$('button[name=bulk_gpt_lock]').closest('.card').find('textarea, input, button, select').not('button[name=bulk_gpt_lock]').prop('disabled', 'disabled');
		};
		window.panelUnlock = () => {
			$('button[name=bulk_gpt_lock]').data('is-locked', false).find('i').removeClass('fa-lock').addClass('fa-unlock');
			$('button[name=bulk_gpt_lock]').closest('.card').find('textarea, input, button, select').not('button[name=bulk_gpt_lock]').prop('disabled', false);
		};
		$('#form_post_bulk').on('submit', function(e) {
			e.preventDefault();
			$.post({url: '', data: $(this).serialize(), context: this}).done(() => {
				$('[name=json]').val('');
				if(typeof window.taskSuccessHandler === 'function') window.taskSuccessHandler();
			});
		});
		$('#modal-preview').on('shown.bs.modal', function() {
			$('.modal-footer button[data-submit-target]').first().focus();
		});
		$('#modal-preview').on('show.bs.modal', function() {
			try {
				let data = JSON.parse($('[name=json]').val());
				let $content = $('<article>').html(marked.parse(data.content || data.article));
				let img_count = $content.find('img').length;
				let structure = $content.children().map(function () {
					var $children = $(this).children();
					if ($children.length === 0) {
						return this.tagName; // Return its own tag name if no children
					} else if ($children.length === 1 && $children[0].nodeType === Node.ELEMENT_NODE && this.tagName === 'P') {
						return $children[0].tagName; // Return child tag name if it has one child element
					} else {
						return this.tagName; // Return its own tag name if multiple children or text node
					}
				}).get().join(' ').replace(/IMG/g, '<b>IMG</b>');
				$('#modal-preview .modal-body').empty().append(
					$('<h2>').text(data.title),
					`<h4>Image count: ${img_count}</h4>`,
					$(`<span>`).html(`Structure: ${structure}`),
					$content
				);
			} catch (err) {
				$('#modal-preview .modal-body').empty().append('Preview unavailable');
			}
		});
		$('textarea').on('keydown', function(e) {
			if (e.ctrlKey && e.keyCode === 13) {
				e.preventDefault();
				$(this).attr('form') ? $('#'+$(this).attr('form')).submit() : $(this).parents('form').submit();
			}
		});
		for (const [key, value] of (new URLSearchParams(location.search)).entries()) {
			var $el = $('[name="'+key+'"]');
			// console.log($el)
			switch($el.prop('tagName')) {
				case 'INPUT':
					var type = $el.prop('type');
					if(type == 'checkbox') $el.prop('checked', 'checked');
					else if(type == 'radio') $('[name="'+key+'"][value="'+value+'"]').prop('checked', 'checked').click();
					else $el.val(value);
					break;
				case 'SELECT':
				case 'TEXTAREA':
				default: $el.val(value);
			}
		}
	},
	beforeSendHandler: function() {
		$(this).closest('.card').find('.overlay').show();
	},
	completeHandler: function() {
		$(this).closest('.card').find('.overlay').hide();
	},
	successHandler: function(response) {
		let data = response.data || [];
		if(response.message) toastr.empty().success(response.message);
		data.posts_count_all      != undefined && $('span.posts_count_all').text(data.posts_count_all);
		data.posts_count_publish  != undefined && $('span.posts_count_publish').text(data.posts_count_publish);
		data.posts_count_schedule != undefined && $('span.posts_count_schedule').text(data.posts_count_schedule);
		data.posts_count_draft    != undefined && $('span.posts_count_draft').text(data.posts_count_draft);
		data.posts_count_trash    != undefined && $('span.posts_count_trash').text(data.posts_count_trash);
		if(data.handler && 'function' === typeof app[data.handler]) app[data.handler](data);
	},
};
</script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
