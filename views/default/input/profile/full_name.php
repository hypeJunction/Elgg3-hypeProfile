<?php

$name = elgg_extract('name', $vars);
$value = (array) elgg_extract('value', $vars, []);

$fields = [
	[
		'#type' => 'text',
		'#label' => elgg_echo("field:user:first_name"),
		'name' => "{$name}[first_name]",
		'value' => elgg_extract('first_name', $value),
		'required' => elgg_extract('required', $vars),
		'data-parsley-trigger' => elgg_extract('data-parsley-trigger', $vars),
		'data-parsley-debounce' => elgg_extract('data-parsley-debounce', $vars),
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo("field:user:last_name"),
		'name' => "{$name}[last_name]",
		'value' => elgg_extract('last_name', $value),
		'required' => elgg_extract('required', $vars),
		'data-parsley-trigger' => elgg_extract('data-parsley-trigger', $vars),
		'data-parsley-debounce' => elgg_extract('data-parsley-debounce', $vars),
	],
];

echo elgg_view_field([
	'#type' => 'fieldset',
	'#class' => 'elgg-field-composite',
	'align' => 'horizontal',
	'fields' => $fields,
]);
