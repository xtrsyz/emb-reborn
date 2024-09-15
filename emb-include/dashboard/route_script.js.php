<?php header('Content-Type: application/javascript') ?>
// <script>
toastr.options.progressBar = true;
toastr.options.timeOut = 8000;
toastr.empty = () => {
	toastr.remove();
	return toastr;
}
var escapeHTML = (str) => {
	var map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
	return str.replace(/[&<>"']/g, function(m) { return map[m]; });
};
var templateEngine = (str, data) => {
	// https://gist.github.com/iiidefix
	return str.replace(/\{\{([^\}]+)\}\}/g, function (m, n) {
		n = n.trim();
		var o = data, p = n.split('|')[0].split('.').map(v => v.split(','));
		for(const [key, ...args] of p) {
			// console.log([key, typeof o[key], typeof window[key], ...args]);
			o = "function" === typeof o[key] ? o[key](...args) : "function" === typeof window[key] ? window[key](o, ...args) : o[key];
			if ("undefined" === typeof o || o === null) {
				o = n.indexOf('|') !== -1 ? n.split('|')[1] : m;
				break;
			}
		}
		return o;
	});
};
var numFormat = (n, precision = 1) => Intl.NumberFormat('en-US', {notation: "compact", maximumFractionDigits: precision}).format(n);
var errorHandler = jqXHR => {
	let response = jqXHR.responseJSON || [];
	let errors = response.errors || [];
	let message = response.message || errors.message || 'An error occurred.';
	toastr.empty().error(message, 'Error', {timeOut: 0, extendedTimeOut: 0});
	if(jqXHR.status == 401)
		$('.modal.login').modal('show');
}
if(typeof app === 'undefined') var app = {init: () => 1};
$(function() {
	$(document).ajaxSend((event, jqXHR, ajaxOptions) => $.current_request = ajaxOptions);
	$('.modal.login').on('shown.bs.modal', function() {
		$(this).find('[autofocus]').focus();
	}).find('form').on('submit', function(e) {
		e.preventDefault();
		$.ajax({
			method: 'POST',
			url: this.action,
			data: $(this).serialize(),
			global: false,
			dataType: 'json',
			error: errorHandler,
			beforeSend: () => {
				toastr.empty().info('Logging in ...', null, {timeOut: 0, extendedTimeOut: 0});
				$(this).find('button[type="submit"]').text('Logging in ...');
			},
			success: () => {
				$('#login-alert').empty();
				$('.modal.login').modal('hide');
				toastr.empty().info('You are now logged in.');
				if($.current_request) $.ajax($.current_request);
			},
			complete: () => {
				$(this).find('button[type="submit"]').text('Login');
			},
		});
	});
	// table sorter
	(function tableSorterInit() {
		const getCellValue = (row, index) => $(row).children('td').eq(index).data('value') || $(row).children('td').eq(index).text();
		const comparer = index => (a, b) => {
			var valA = getCellValue(a, index), valB = getCellValue(b, index);
			return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB);
		};
		const sortClickHandler = function(e) {
			if(!$(this).text().trim()) return;
			let $tbody = $(this).closest('table').find('tbody');
			let rows = $tbody.children('tr:not(.no-sort)').toArray().sort(comparer($(this).index()));
			this.asc = !this.asc;
			if(!this.asc) rows = rows.reverse();
			rows.forEach(row => $tbody.append(row));
		};
		$('th:not(.no-sort)').on('click', sortClickHandler);
	})();
	// table filter
	(function tableFilterInit() {
		$('[name="table_search"]').on('submit', function(e) {
			e.preventDefault();
			$(e.target).closest('.card').find('tbody tr:not(.no-filter)').each(
				(i, row) => $(row).toggle(row.innerText.toLowerCase().includes(e.target[0].value.trim().toLowerCase()))
			);
			if(typeof app !== 'undefined' && typeof app.additionalTableFilterHandler === 'function') app.additionalTableFilterHandler();
		});
		$('[name="table_search"]').on('reset', e => {
			$(e.target).closest('.card').find('tbody tr:not(.no-filter)').show();
			if(typeof app !== 'undefined' && typeof app.additionalTableFilterHandler === 'function') app.additionalTableFilterHandler();
		});
		$('[name="table_search"] [type="search"]').on('input change keyup', e => $('[name="table_search"]').trigger('submit'));
	})();
	if(typeof app !== 'undefined' && typeof app.init === 'function') app.init();

	// config editor
	function createCell(value) {
		var input;
		switch(typeof value) {
			case 'boolean': input = createInputBoolean(value); break;
			case 'object' : input = Array.isArray(value) ? createInputsArray(value) : createInputsObject(value); break;
			case 'number' : input = createInputString(value); break;
			default       : input = createInputString(value);
				// case 'string':
		}
		return $(`<td class="w-100"><form data-option-form-update>${input}</form></td>`);
	}
	function createRow(key, value) {
		var data_type = typeof value;
		var cell = createCell(value);
		var row = $(`<tr data-key="${key}" data-type="${data_type}"><td class="text-nowrap">${key}</td></tr>`);
		row.append(cell);
		row.data('value', typeof value == 'object' ? JSON.stringify(value) : value);
		return row;
	}
	function deleteRow(table, key) {
		table.find(`tr[data-key=${key}`).remove();
	}
	function updateRow(table, key, value) {
		var tr_hr = table.find('tr td[colspan] hr').closest('tr');
		var existing_row = table.find(`tr[data-key=${key}`);
		var row = createRow(key, value);
		if(existing_row.length)
			existing_row.replaceWith(row)
		else if(tr_hr.length)
			row.insertBefore(tr_hr);
		else
			table.find('tbody').append(row);
	}
	function createInputBoolean(value) {
		var checked = value ? 'checked' : '';
		value = value ? 'true' : 'false';
		return `<div class="form-check form-switch mb-0"><input data-option-input class="form-check-input" type="checkbox" role="switch" value="${value}" ${checked}></div>`
	}
	function createInputString(value) {
		value = escapeHTML(value.toString());
		if(value.indexOf('\n') !== -1)
			return `<textarea data-option-input class="form-control form-control-xs rounded-0 px-1 py-0 small">${value}</textarea>`;
		else
			return `<input data-option-input class="form-control form-control-xs rounded-0 px-1 py-0 small" value="${value}">`;
	}
	function createInputArray(value) {
		var input = `<div class="mb-1 input-group">`;
		switch(typeof value) {
			case 'object':
				value = JSON.stringify(value);
			default:
				value = escapeHTML(value.toString());
				if(value.indexOf('\n') !== -1)
					input += `<textarea data-option-array-input class="form-control form-control-xs rounded-0 px-1 py-0 small">${value}</textarea>`;
				else
					input += `<input data-option-array-input class="form-control form-control-xs rounded-0 px-1 py-0 small" value="${value}">`;
			// case 'number':
			// case 'string':
		}
		input += `<button data-option-array-remove type="button" class="btn btn-sm btn-outline-danger rounded-0 ms-sm-2 py-0"><i class="fas fa-trash"></i></button>`;
		input += `</div>`;
		return input;
	}
	function createInputsArray(values) {
		var input = `<div class="mb-1">
<button data-option-raw type="button" class="btn btn-sm btn-outline-primary rounded-0 py-0">Edit Raw</button>
Array:
</div>`;
		input += `<div data-input-array-wrapper>`;
		values.forEach(value => {
			input += createInputArray(value);
		});
		input += `</div>`;
		input += `<div class="input-group">
<input data-option-array-input-new class="form-control form-control-xs rounded-0 px-1 py-0 small" placeholder="New item">
<button data-option-array-button-new type="submit" class="btn btn-sm btn-outline-primary rounded-0 ms-sm-2 py-0"><i class="fas fa-plus"></i></button>
</div>
<hr class="mt-1 mb-0">`;
		return input;
	}
	function createInputObject(name, value) {
		var input = `<div class="row">`;
		input += `<div class="col-5 col-sm-4 col-md-3 pe-0"><input data-option-object-input-key class="form-control form-control-xs rounded-0 mb-1 me-1 px-1 py-0 small"placeholder="name" value="${name}"></div>`;
		input += `<div class="col-7 col-sm-8 col-md-9 ps-1">`;
		input += `<div class="input-group mb-1">`;
		switch(typeof value) {
			case 'boolean':
				var checked = value ? 'checked' : '';
				value = value.toString();
				input += `<div class="form-check form-switch"><input data-option-object-input-value class="form-check-input" type="checkbox" role="switch" value="${value}" ${checked}></div>`;
				break;
			case 'object':
				value = JSON.stringify(value);
			case 'number':
				value = value.toString();
			case 'string':
			default:
				value = escapeHTML(value);
				if(value.indexOf('\n') !== -1)
					input += `<textarea data-option-object-input-value class="form-control form-control-xs rounded-0 px-1 py-0 small">${value}</textarea>`;
				else
					input += `<input data-option-object-input-value class="form-control form-control-xs rounded-0 px-1 py-0 small" value="${value}">`;
		}
		input += `<button data-option-object-remove type="button" class="btn btn-sm btn-outline-danger rounded-0 ms-sm-2 py-0"><i class="fas fa-trash"></i></button>`;
		input += `</div>`;
		input += `</div>`;
		input += `</div>`;
		return input;
	}
	function createInputsObject(values) {
		var input = `<div class="mb-1">
<button data-option-raw type="button" class="btn btn-sm btn-outline-primary rounded-0 py-0">Edit Raw</button>
Assoc Array:
</div>`;
		input += `<div data-input-object-wrapper>`;
		for(var [name, value] of Object.entries(values)) {
			input += createInputObject(name, value);
		}
		input += `</div>`;
		input += `<div class="row">
<div class="col-5 col-sm-4 col-md-3 pe-0"><input data-option-object-input-new-name class="form-control form-control-xs rounded-0 px-1 py-0 small"placeholder="name"></div>
<div class="col-7 col-sm-8 col-md-9 ps-1">
<div class="input-group">
<input data-option-object-input-new-value class="form-control form-control-xs rounded-0 px-1 py-0 small" placeholder="value">
<button data-option-object-button-new type="submit" class="btn btn-sm btn-outline-primary rounded-0 ms-sm-2 py-0"><i class="fas fa-plus"></i></button>
</div>
</div>
</div>
<hr class="mt-1 mb-0">`;
		return input;
	}
	function setOptionSuccessHandler(table, data, message) {
		switch(data.act) {
			case 'delete':
				$.post(DASHBOARD_INDEX_URL+'/getoption', {key: data.key, from: 'delete'}).done(response => {
					toastr.empty().success(message);
					if(response.data.act == 'delete')
						deleteRow(table, response.data.key);
					else
						updateRow(table, response.data.key, response.data.value);
				}).fail(response => toastr.empty().error(message || 'An error occured.'));
				break;
			case 'nothing':
				toastr.empty().info(message);
				break;
			default:
				toastr.empty().success(message);
				updateRow(table, data.key, data.value);
		}
	}
	function getInputValueFromRow(row) {
		var type  = row.data('type');
		var key   = row.data('key');
		switch(row.data('type')) {
			case 'object':
				var new_values;
				if(row.find('[data-input-object-wrapper]').length) {
					new_values = {};
					row.find('[data-input-object-wrapper] .row').each(function() {
						var key = $(this).find('[data-option-object-input-key]').val();
						var value = $(this).find('[data-option-object-input-value]').val();
						new_values[key] = value;
					});
				} else if(row.find('[data-input-array-wrapper]').length) {
					new_values = [];
					row.find('[data-option-array-input]').each(function() {
						new_values.push($(this).val());
					});
				} else {
					try {
						new_values = JSON.parse(row.find('[data-option-input]').val());
					} catch(e) {
						new_values = row.find('[data-option-input]').val();
					}
				}
				return JSON.stringify(new_values);
			default:
				return row.find('input,textarea').val();
		}
	}
	$('[data-option-rows]').each(function() {
		var table = $(this).closest('table');
		var rows = JSON.parse($(this).text());
		if(rows.length) {
			$(this).next().remove();
			rows.forEach(item => updateRow(table, ...item));
		} else {
			$(this).next('[data-option-no-rows]').find('td').text('No config in this segment.');
		}
	});

    // Handle focus event on transformed input/textarea
    $('[data-option-table]').on('keydown', '[data-option-input],[xdata-option-array-input],[xdata-option-object-input]', function(event) {
		if($(this).is('[type=checkbox]')) return;
		var row = $(this).closest('tr');
		var type = row.data('type');
		var cell = $(this).closest('td');
        var key = row.data('key');
        var old_value = row.data('value');
		var new_value = $(this).val();
        // Handle Esc key press to revert back
		if (event.keyCode === 27) {
			// var cell = $(createCell(old_value));
			var new_cell = createCell(type == 'object' ? JSON.parse(old_value) : old_value);
			cell.replaceWith(new_cell);
			var input = new_cell.find('input, textarea');
			input.focus();
			var len = input.val().length;
			input[0].setSelectionRange(len, len);
		}
		// Handle Shift+Enter to transform input into textarea (multiline mode)
		else if (event.shiftKey && event.keyCode === 13 && $(this).is('input')) {
			var cell = $(this).closest('td');
			var new_cell = createCell('\n');
			cell.replaceWith(new_cell);
			new_cell.find('textarea').val(new_value).addClass('border-danger').focus().closest('tr').attr('data-changed', 1);
		}
		// Handle Ctrl+Enter to submit form
		else if (event.ctrlKey && event.keyCode === 13 && $(this).is('textarea')) {
			$(this.form).trigger('submit');
		}
    });
	$('[data-option-table]').on('input', '[data-option-input]:not([type=checkbox])', function() {
		var row = $(this).closest('tr');
		var old_value = row.data('value');
		var new_value = $(this).val();
		if(new_value != old_value)
			$(this).addClass('border-danger').closest('tr').attr('data-changed', 1);
		else
			$(this).removeClass('border-danger').closest('tr').removeAttr('data-changed');
	});
	$('[data-option-table]').on('click', '[data-option-input][type=checkbox]', function() {
		var row = $(this).closest('tr');
		var old_value = row.data('value');
		var new_value = $(this).prop('checked') ? 'true' : 'false';
		if(new_value != old_value)
			$(this).addClass('bg-danger').closest('tr').attr('data-changed', 1);
		else
			$(this).removeClass('bg-danger').closest('tr').removeAttr('data-changed');
		this.value = new_value;
	});
	$('[data-option-table]').on('click', 'button[data-option-raw]', function() {
		var row = $(this).closest('tr');
		var cell = $(this).closest('td');
		var value = JSON.stringify(JSON.parse(getInputValueFromRow(row)), null, 2);
		var new_cell = createCell(value);
		cell.replaceWith(new_cell);
	});
	$('[data-option-table]').on('submit', 'form[data-option-form-add]', function(e) {
		e.preventDefault();
		var table = $(this).closest('table');
		var els = $(this).closest('tr').find('button, input');
		var prefix = table.data('option-prefix');
		var payload = $(this).serialize();
		els.attr('disabled', true);
		$.post(DASHBOARD_INDEX_URL+'/setoption', payload).done(response => {
			$(this).closest('tr').find('input:not([type=hidden])').val('');
			$(this).closest('table').find('[data-option-no-rows]').remove();
			setOptionSuccessHandler(table, response.data, response.message);
		}).fail(err => {
			errorHandler(err);
		}).always(() => els.removeAttr('disabled'));
	});
	$('[data-option-table]').on('click', '[data-option-array-button-new]', function(e) {
		var table = $(this).closest('table');
		var row   = $(this).closest('tr');
		var key   = row.data('key');
		var new_item = $(this).prev().val();
		if(new_item) {
			e.preventDefault();
			var input = createInputArray(new_item);
			$(this.form).find('[data-input-array-wrapper]').append(input);
			$(this).prev().val('');
			row.attr('data-changed', 1);
		}
	});
	$('[data-option-table]').on('click', '[data-option-object-button-new]', function(e) {
		var table = $(this).closest('table');
		var row   = $(this).closest('tr');
		var new_name = $(this).closest('div.row').find('[data-option-object-input-new-name]').val();
		var new_value = $(this).closest('div.row').find('[data-option-object-input-new-value]').val();
		if(!new_name || !new_value) return;
		if(new_name && new_value) {
			e.preventDefault();
			$(this.form).find('[data-input-object-wrapper]').append(createInputObject(new_name, new_value));
			$(this).closest('div.row').find('input').val('');
			row.attr('data-changed', 1);
		}
	});
	$('[data-option-table]').on('click', '[data-option-array-remove]', function(e) {
		$(this).closest('tr').attr('data-changed', 1);
		$(this).closest('.input-group').remove();
	});
	$('[data-option-table]').on('click', '[data-option-object-remove]', function(e) {
		$(this).closest('tr').attr('data-changed', 1);
		$(this).closest('.row').remove();
	});
	$('[data-option-table]').on('submit', 'form[data-option-form-update]', function(e) {
		e.preventDefault();
		var table = $(this).closest('table');
		var row = $(this).closest('tr');
		var old_value = row.data('value');
		var disabled = row.find('input,textarea,button');
		var key   = row.data('key');
		var value = getInputValueFromRow(row);
		if(old_value === value)
			return toastr.empty().info('Nothing changed');
		disabled.attr('disabled', true);
		$.post(DASHBOARD_INDEX_URL+'/setoption', {key, value}).done(response => {
			setOptionSuccessHandler(table, response.data, response.message)
		}).fail(err => {
			disabled.removeAttr('disabled');
			errorHandler(err);
		});
	});
	$('[data-option-button-save-changes], [data-option-button-save-all]').on('click', function() {
		var table = $(this).closest('.card').find('table');
		var inputs_selector = typeof $(this).attr('data-option-button-save-changes') != 'undefined' ? 'tr[data-key][data-changed]' : 'tr[data-key]';
		var rows = $(this).closest('.card').find(inputs_selector);
		var options = [];
		rows.each(function() {
			var row   = $(this);
			var key   = row.data('key');
			var value = getInputValueFromRow(row);
			options.push({key, value});
			$(this).attr('disabled', true);
			row.find('[data-option-input],[data-option-array-input],[data-option-object-input-key],[data-option-object-input-value]').attr('disabled', true);
		});
		if(!options.length)
			return null;
		$(this).attr('disabled', true);
		$.post(DASHBOARD_INDEX_URL+'/setoption', {options}).done(response => {
			var deleted_keys = [];
			toastr.empty().success(response.message);
			for(const data of response.data) {
				switch(data.act) {
					case 'delete':
						table.find(`tr[data-key=${data.key}] input,tr[data-key=${data.key}] textarea`).attr('disabled', true);
						deleted_keys.push(data.key);
						break;
					default:
						updateRow(table, data.key, data.value);
				}
			}
			if(deleted_keys.length) {
				$.post(DASHBOARD_INDEX_URL+'/getoption', {keys: deleted_keys}).done(response => {
					for(const data of response.data) {
						if(data.value === null)
							deleteRow(table, data.key);
						else
							updateRow(table, data.key, data.value);
					}
				});
			}
		}).fail(err => {
			errorHandler(err);
		}).always(() => $(this).removeAttr('disabled'));
	});
});

document.querySelectorAll('.navbar a.nav-link').forEach(el => el.href != location.href.split(/[?#]/)[0] || el.classList.add('active'));
document.querySelectorAll('.sidebar a.nav-link').forEach(el => el.href != location.href.split(/[?#]/)[0] || el.classList.add('bg-body-secondary'));

/*!
 * Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
 * Copyright 2011-2023 The Bootstrap Authors
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 */

(() => {
	'use strict'

	const toggleValue = (currentValue, value1, value2) => currentValue === value1 ? value2 : value1;
	const getStoredTheme = () => localStorage.getItem('theme')
	const setStoredTheme = theme => localStorage.setItem('theme', theme)

	const getPreferredTheme = () => {
		const storedTheme = getStoredTheme()
		if (storedTheme) {
			return storedTheme
		}

		return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
	}

	const setTheme = theme => {
		if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
			document.documentElement.setAttribute('data-bs-theme', 'dark')
		} else {
			document.documentElement.setAttribute('data-bs-theme', theme)
		}
	}

	setTheme(getPreferredTheme())

	const showActiveTheme = (theme, focus = false) => {
		const themeSwitcher = document.querySelector('#bd-themexxx')

		if (!themeSwitcher) {
			return
		}

		const themeSwitcherText = document.querySelector('#bd-theme-text')
		const activeThemeIcon = document.querySelector('.theme-icon-active use')
		const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
		const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href')

		document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
			element.classList.remove('active')
			element.setAttribute('aria-pressed', 'false')
		})

		btnToActive.classList.add('active')
		btnToActive.setAttribute('aria-pressed', 'true')
		activeThemeIcon.setAttribute('href', svgOfActiveBtn)
		const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`
		themeSwitcher.setAttribute('aria-label', themeSwitcherLabel)

		if (focus) {
			themeSwitcher.focus()
		}
	}

	window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
		const storedTheme = getStoredTheme()
		if (storedTheme !== 'light' && storedTheme !== 'dark') {
			setTheme(getPreferredTheme())
		}
	})

	window.addEventListener('DOMContentLoaded', () => {
		showActiveTheme(getPreferredTheme())

		document.querySelectorAll('[data-bs-theme-value]')
			.forEach(toggle => {
			toggle.addEventListener('click', () => {
				const theme = toggle.getAttribute('data-bs-theme-value')
				setStoredTheme(theme)
				setTheme(theme)
				showActiveTheme(theme, true)
			})
		})
		document.querySelectorAll('[data-bs-theme-toggle]')
			.forEach(toggle => {
			toggle.addEventListener('click', (e) => {
				e.preventDefault()
				const storedTheme = getStoredTheme()
				const theme = toggleValue(storedTheme, 'dark', 'light')
				setStoredTheme(theme)
				setTheme(theme)
				// showActiveTheme(theme, true)
			})
		})
	})
})()
// </script>
<?php exit ?>
