<?php

$fields = [
	[
		'#type' => 'hidden',
		'name' => 'friend_guid',
		'value' => get_input('friend_guid'),
	],
	[
		'#type' => 'hidden',
		'name' => 'invitecode',
		'value' => get_input('invite_code'),
	],
	[
		'#type' => 'hidden',
		'name' => 'subtype',
		'value' => get_input('subtype'),
	],
	[
		'#type' => 'email',
		'#label' => elgg_echo('preregister:email'),
		'#help' => elgg_echo('preregister:email:help'),
		'required' => true,
		'name' => 'email',
	],
	[
		'#type' => 'captcha',
	],
];


$q = (array) elgg_extract('q', $vars);
foreach ($q as $key => $val) {
	$fields[] = [
		'#type' => 'hidden',
		'name' => "q[$key]",
		'value' => $val,
	];
}

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('preregister:continue'),
]);

elgg_set_form_footer($footer);