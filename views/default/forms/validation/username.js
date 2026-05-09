import $ from 'jquery';
import Ajax from 'elgg/Ajax';
import 'forms/validation';

window.Parsley.addValidator('validusername', {
	requirementType: 'string',
	validateString: function (value, url) {
		var promise = $.Deferred();

		var ajax = new Ajax(false);
		ajax.path(url, {
			data: {
				username: value
			}
		}).done(function(data) {
			if (data.valid) {
				promise.resolve(true);
			} else {
				promise.reject(false);
			}
		});

		return promise;
	},
	messages: {
		_: 'validation:error:type:validusername'
	}
});

window.Parsley.addValidator('availableusername', {
	requirementType: 'string',
	validateString: function (value, url) {
		var promise = $.Deferred();

		var ajax = new Ajax(false);
		ajax.path(url, {
			data: {
				username: value
			}
		}).done(function(data) {
			if (data.available) {
				promise.resolve(true);
			} else {
				promise.reject(false);
			}
		});

		return promise;
	},
	messages: {
		_: 'validation:error:type:availableusername'
	}
});
