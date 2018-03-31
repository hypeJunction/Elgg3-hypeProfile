<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'params[email_validation]',
	'value' => $entity->first_last_name,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'#label' => elgg_echo('settings:forms:register:email_validation'),
	'#help' => elgg_echo('settings:forms:register:email_validation:help'),
]);

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'params[first_last_name]',
	'value' => $entity->first_last_name,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'#label' => elgg_echo('settings:forms:register:first_last_name'),
	'#help' => elgg_echo('settings:forms:register:first_last_name:help'),
]);

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'params[last_name_abbr]',
	'value' => $entity->last_name_abbr,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'#label' => elgg_echo('settings:forms:register:last_name_abbr'),
	'#help' => elgg_echo('settings:forms:register:last_name_abbr:help'),
]);

echo elgg_view_field(array(
	'#type' => 'select',
	'name' => 'params[autogen_name]',
	'value' => $entity->autogen_name,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'#label' => elgg_echo('settings:forms:register:autogen_name'),
	'#help' => elgg_echo('settings:forms:register:autogen_name:help'),
));

echo elgg_view_field(array(
	'#type' => 'select',
	'name' => 'params[autogen_username]',
	'value' => $entity->autogen_username,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'#label' => elgg_echo('settings:forms:register:autogen_username'),
	'#help' => elgg_echo('settings:forms:register:autogen_username:help'),
));

echo elgg_view_field(array(
	'#type' => 'select',
	'name' => 'params[autogen_username_algo]',
	'value' => $entity->autogen_username_algo,
	'options_values' => array(
		'first_name_only' => elgg_echo('settings:forms:register:autogen_username_algo:first_name_only'),
		'full_name' => elgg_echo('settings:forms:register:autogen_username_algo:full_name'),
		'email' => elgg_echo('settings:forms:register:autogen_username_algo:email'),
		'alnum' => elgg_echo('settings:forms:register:autogen_username_algo:alnum'),
	),
	'#label' => elgg_echo('settings:forms:register:autogen_username_algo'),
	'#help' => elgg_echo('settings:forms:register:autogen_username_algo:help'),
));

echo elgg_view_field(array(
	'#type' => 'select',
	'name' => 'params[autogen_password]',
	'value' => $entity->autogen_password,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'#label' => elgg_echo('settings:forms:register:autogen_password'),
	'#help' => elgg_echo('settings:forms:register:autogen_password:help'),
));

echo elgg_view_field(array(
	'#type' => 'select',
	'name' => 'params[hide_password_repeat]',
	'value' => $entity->hide_password_repeat,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'#label' => elgg_echo('settings:forms:register:hide_password_repeat'),
	'#help' => elgg_echo('settings:forms:register:hide_password_repeat:help'),
));

echo elgg_view_field(array(
	'#type' => 'select',
	'name' => 'params[min_password_strength]',
	'value' => $entity->min_password_strength,
	'options_values' => array(
		0 => elgg_echo('settings:forms:register:password:no_strength_check'),
		1 => elgg_echo('settings:forms:register:password:weak'),
		2 => elgg_echo('settings:forms:register:password:medium'),
		3 => elgg_echo('settings:forms:register:password:strong'),
		4 => elgg_echo('settings:forms:register:password:very_strong'),
	),
	'#label' => elgg_echo('settings:forms:register:password:min_strength'),
	'#help' => elgg_echo('settings:forms:register:password:min_strength:help'),
));

echo elgg_view_field(array(
	'#type' => 'select',
	'name' => 'params[icon_input]',
	'value' => $entity->icon_input,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'#label' => elgg_echo('settings:forms:register:icon_input'),
	'#help' => elgg_echo('settings:forms:register:icon_input:help'),
));

echo elgg_view_field(array(
	'#type' => 'text',
	'name' => 'params[terms_url]',
	'value' => $entity->terms_url,
	'#label' => elgg_echo('settings:forms:register:terms_url'),
	'#help' => elgg_echo('settings:forms:register:terms_url:help'),
));

echo elgg_view_field(array(
	'#type' => 'text',
	'name' => 'params[privacy_url]',
	'value' => $entity->privacy_url,
	'#label' => elgg_echo('settings:forms:register:privacy_url'),
	'#help' => elgg_echo('settings:forms:register:privacy_url:help'),
));

echo elgg_view_field(array(
	'#type' => 'longtext',
	'name' => 'params[header]',
	'value' => $entity->header,
	'#label' => elgg_echo('settings:forms:register:header'),
	'#help' => elgg_echo('settings:forms:register:header:help'),
));

echo elgg_view_field(array(
	'#type' => 'longtext',
	'name' => 'params[footer]',
	'value' => $entity->footer,
	'#label' => elgg_echo('settings:forms:register:footer'),
	'#help' => elgg_echo('settings:forms:register:footer:help'),
));

echo elgg_view_field(array(
	'#type' => 'longtext',
	'name' => 'params[welcome_email]',
	'value' => $entity->welcome_email,
	'#label' => elgg_echo('settings:forms:register:welcome_email'),
	'#help' => elgg_echo('settings:forms:register:welcome_email:help'),
));
