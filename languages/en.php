<?php

return [

	'settings:forms:register:password:min_strength' => 'Minimum password strength',
	'settings:forms:register:password:min_strength:help' => 'Prevent user registration with weak passwords',
	'settings:forms:register:password:no_strength_check' => 'Do not check',
	'settings:forms:register:password:weak' => 'Weak (score 1)',
	'settings:forms:register:password:medium' => 'Medium (score 2)',
	'settings:forms:register:password:strong' => 'Strong (score 3)',
	'settings:forms:register:password:very_strong' => 'Very strong (score 4)',

	'settings:forms:register:email_validation' => 'Email Validation',
	'settings:forms:register:email_validation:help' => 'If enabled, users will be requested to enter a code sent to them via email to validate that their email account is legit. This eliminates the complicated workflow implemented in the uservalidationbyemail plugin, namely thousands of unvalidated spam accounts are no longer created.',

	'settings:forms:register:first_last_name' => 'First and Last name',
	'settings:forms:register:first_last_name:help' => 'Replace Display name field with First and Last name fields',

	'settings:forms:register:last_name_abbr' => 'Abbreviate Last Name',
	'settings:forms:register:last_name_abbr:help' => 'If enabled, only the first letter of the last name will be used in user-facing labels',
	'profile:last_name_abbr:help' => 'Please provide your full last name for our internal records. Other users will only be able to see the first letter of your last name.',

	'settings:forms:register:autogen_name' => 'Autogenerate name',
	'settings:forms:register:autogen_name:help' => 'Remove display name field from the registration form, and generate display name based on email (or first and last name, if enabled)',

	'settings:forms:register:autogen_username' => 'Autogenerate username',
	'settings:forms:register:autogen_username:help' => 'Remove username field from the registration form, and generate it based on email (or first and last name, if enabled)',

	'settings:forms:register:autogen_username_algo' => 'Username generating algorithm',
	'settings:forms:register:autogen_username_algo:help' => 'Specify which algorithm is to be used when generating the username. On username collisions, or usernames being too short, all algorithms will suffix the username',
	'settings:forms:register:autogen_username_algo:first_name_only' => 'First name only',
	'settings:forms:register:autogen_username_algo:full_name' => 'First and last name with a dot separator',
	'settings:forms:register:autogen_username_algo:email' => 'Username extracted from email address',
	'settings:forms:register:autogen_username_algo:alnum' => 'Random alpha numeric characters',

	'settings:forms:register:autogen_password' => 'Autogenerate password',
	'settings:forms:register:autogen_password:help' => 'Remove password fields, and generate a high entropy random password',

	'settings:forms:register:hide_password_repeat' => 'Hide repeat password',
	'settings:forms:register:hide_password_repeat:help' => 'Remove repeat password field',

	'settings:forms:register:icon_input' => 'Icon input',
	'settings:forms:register:icon_input:help' => 'Display avatar input on the registration form',

	'settings:forms:register:terms_url' => 'Terms and Conditions',
	'settings:forms:register:terms_url:help' => 'Provide a URL or page path to Terms and Conditions of your site. If provided, a checkboxes prompting the user to agree to them will be displayed',

	'settings:forms:register:privacy_url' => 'Privacy Policy',
	'settings:forms:register:privacy_url:help' => 'Provide a URL or page path to Privacy Policy of your site. If provided, a checkboxes prompting the user to agree to it will be displayed',

	'settings:forms:register:header' => 'Intro text',
	'settings:forms:register:header:help' => 'Text to add above the registration form (flush the caches for changes to take effect)',

	'settings:forms:register:footer' => 'Footer text',
	'settings:forms:register:footer:help' => 'Text to add below the registration form (flush the caches for changes to take effect)',

	'settings:forms:register:welcome_email' => 'Welcome Email',
	'settings:forms:register:welcome_email:help' => '
		If you would like users to receive an email once their account is created and validated, enter it here.<br />
		You can use the following mail merge parameters:<br />
		 - {{name}} - corresponds to users\'s first name (or if not set to user\'s display name)<br />
		 - {{profile_url}} - corresponds to the profile URL<br />
		 - {{site_name}} - corresponds to the configured site name<br />
		 - {{site_url}} - corresponds to the site URL<br />
	',

	'forms:register:header' => elgg_get_plugin_setting('header', 'hypeProfile', ''),
	'forms:register:footer' => elgg_get_plugin_setting('footer', 'hypeProfile', ''),

	'field:user:first_name' => 'First Name',
	'field:user:last_name' => 'Last Name',
	'filed:user:location' => 'Location',
	'field:user:location:help' => '',
	
	'actions:register:error:first_last_name' => 'First and Last name are required',
	'actions:register:error:password_strength' => 'The password is too weak. Please choose a more secure password',

	'register:terms' => 'Terms and Conditions',
	'register:terms:agree' => 'By registering on this site, I agree to %s',

	'register:privacy' => 'Privacy Policy',
	'register:privacy:agree' => 'By registering on this site, I agree to %s',

	'validation:error:type:validusername' => 'This username contains invalid characters.',
	'validation:error:type:availableusername' => 'This username is not available.',
	'validation:error:type:minstrength' => 'The password is too weak',
	'validation:error:type:emailaccount' => 'An account with this email address has already been registered, please login to continue',

	// You can use this translation to replace welcome email defined in plugin settings
	// in case you need to internationalize it
	//'register:welcome_email' => '',
	'register:welcome_email:subject' => 'Welcome to %s',

	'profile:validation:email:subject' => 'Verification code for %s',
	'profile:validation:email:message' => '
		<p>Dear %s,</p>
		
		<p>Please use the following verification code to complete your registration:</p>
		
		<p><h1>%s</h1></p>
	',

	'profile:validation:error' => 'Your email could not be verified. The verification code has been resent to your email address',
	'profile:validation:sent' => 'We have sent a verification code to your email. Please enter it in the verification field',
	'profile:validation:not_sent' => 'Verification code could not be sent to your email',

	'field:user:user:email_validation' => 'Verification Code',
	'field:user:user:email_validation:help' => 'Please enter the verification code that was sent to your email',
	'field:user:user:email_validation:placeholder' => 'Verification Code',

	'edit:user:user' => 'Edit Profile',

	'profile:empty' => 'Get started by providing some more information about yourself. %s',

	'collection:users:users:no_results' => 'There are no users to display',

	'sort:user:filter:label' => 'Filter',
	'sort:user:filter:placeholder' => 'Select ...',
	'sort:user:filter:all' => 'All',
	'sort:user:filter:is_friend' => 'Friends',
	'sort:user:filter:is_not_friend' => 'Not Friends',
	'sort:user:filter:is_online' => 'Online Users',
	'sort:user:filter:placeholder:guids' => '... or choose users',

	'settings:forms:field_access' => 'Profile Field Access',
	'settings:forms:field_access:help' => 'Specify how profile field access is determined',
	'settings:forms:field_access:public' => 'Make all profile fields public',
	'settings:forms:field_access:logged_in' => 'Show all profile fields to registered users only',
	'settings:forms:field_access:field_picker' => 'Let users choose access level for each field',
	'settings:forms:field_access:global_picker' => 'Let users choose access level for all fields at once',

	'field:user:user:profile_field_access' => 'Profile Data Visibility',
	'field:user:user:profile_field_access:help' => 'Select visibility level that should be applied to profile data',

	'success:user:save' => 'Profile details have been successfully saved',

	'system_message::account:pending_approval' => 'Your account is pending approval by site\'s administrator. You will receive an email once your account is approved.'
];
