import zxcvbn from 'zxcvbn/zxcvbn';
import 'forms/validation';

window.Parsley.addValidator('minstrength', {
	requirementType: 'string',
	validateString: function (value, requirement) {
		var result = zxcvbn(value);
		return result.score >= requirement;
	},
	messages: {
		_: 'validation:error:type:minstrength'
	}
});
