<?php

$entity = elgg_extract('entity', $vars);
$fields = elgg_extract('fields', $vars, []);

$view_fields = function (\hypeJunction\Fields\Collection $fields) use ($entity) {
	$output = '';
	foreach ($fields as $field) {
		/* @var $field \hypeJunction\Fields\FieldInterface */
		$output .= $field->render($entity);
	}

	return $output;
};

$filter = function (\hypeJunction\Fields\Collection $fields, $section) {
	return $fields->filter(function (\hypeJunction\Fields\FieldInterface $field) use ($section) {
		return $field->section == $section;
	});
};

$header = elgg_echo('forms:register:header');
if ($header) {
	echo elgg_format_element('div', [
		'class' => 'elgg-form-register-header',
	], $header);
}

$content = $view_fields($filter($fields, 'content'));
if ($content) {
	echo elgg_format_element('div', [
		'class' => 'elgg-grid elgg-fields',
	], $content);
}

$footer = elgg_echo('forms:register:footer');
if ($footer) {
	echo elgg_format_element('div', [
		'class' => 'elgg-form-register-footer',
	], $footer);
}

$actions = $filter($fields, 'actions');
foreach ($actions as $action) {
	/* @var $action \hypeJunction\Fields\FieldInterface */

	$menu_item = [
		'name' => $action->name,
		'href' => false,
		'text' => $action->render($entity),
		'priority' => $action->priority,
	];

	elgg_register_menu_item('form:actions', $menu_item);
}

$footer = elgg_view_menu('form:actions', [
	'class' => 'elgg-menu-hz',
    'data-parsley-validate' => '',
    'data-parsley-trigger' => 'input change',
]);

elgg_set_form_footer($footer);
