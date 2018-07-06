define(function(require) {

	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');
	var $ = require('jquery');
	var lightbox = require('elgg/lightbox');
	var spinner = require('elgg/spinner');
	require('forms/validation');

	var $form = $('.elgg-form-register');

	$form.parsley();

	var Form = require('ajax/Form');
	var form = new Form($form);

	form.onSubmit(function(resolve, reject) {
		var $validation = $form.find('[name="email_validation"]');

		if ($validation.length && (!$validation.val() && $validation.data('required'))) {
			var ajax = new Ajax();
			ajax.action('validation/send_code', {
				data: ajax.objectify($form)
			}).done(function() {
				$validation.closest('.elgg-field').removeClass('hidden').fadeIn();
				$validation.eq(0).focus();
			});

			return reject(new Error('Email validation required'));
		}

		return resolve();
	});

});