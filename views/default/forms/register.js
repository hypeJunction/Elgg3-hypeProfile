define(function(require) {

	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');
	var $ = require('jquery');
	var lightbox = require('elgg/lightbox');
	var spinner = require('elgg/spinner');
	require('forms/validation');

	$('.elgg-form-register').parsley();

	$(document).on('submit', '.elgg-form-register', function(e) {

		e.preventDefault();

		var $form = $(this);
		var ajax;

		$form.find('[type="submit"]').prop('disabled', true);

		var $validation = $form.find('[name="email_validation"]');

		if ($validation.length && (!$validation.val() && $validation.data('required'))) {
			$('.elgg-form-register').parsley();
			ajax.action('validation/send_code', {
				data: ajax.objectify($form)
			}).done(function() {
				$validation.closest('.elgg-field').removeClass('hidden').fadeIn();
				$validation.eq(0).focus();
				$form.find('[type="submit"]').prop('disabled', false);
			});

			return false;
		}

		ajax = new Ajax();
		ajax.action($form.attr('action'), {
			data: ajax.objectify($form),
			beforeSend: function() {
				spinner.start();
				$form.find('[type="submit"]').prop('disabled', true);
			}
		}).done(function(data, statusText, xhr) {
			spinner.stop();
			if (xhr.AjaxData.status === -1) {
				$form.find('[type="submit"]').prop('disabled', false);
				return;
			}

			if ($form.closest('#colorbox').length) {
				lightbox.close();
			}

			ajax.forward(xhr.AjaxData.forward_url || data.forward_url || elgg.normalize_url(''));
		}).fail(function() {
			$form.find('[type="submit"]').prop('disabled', false);
		});
	});
});