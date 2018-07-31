<?php

$request = elgg_extract('request', $vars);
/* @var $request \Elgg\Request */

$subtype = elgg_extract('subtype', $vars, 'user');
$constructor = elgg_get_entity_class('user', $subtype);
if (!$constructor || !is_subclass_of($constructor, ElggUser::class)) {
	$constructor = ElggUser::class;
}

$title = elgg_echo('register');
if (elgg_language_key_exists("register:$subtype")) {
	$title = elgg_echo("register:$subtype");
}

$entity = new $constructor();
/* @var $entity ElggUser */

$svc = \hypeJunction\Post\Model::instance();

$vars = $request->getParams();
$vars['context'] = \hypeJunction\Fields\Field::CONTEXT_CREATE_FORM;
$form_vars = $svc->getFormVars($entity, $vars);

$sticky = elgg_get_sticky_values('register');
elgg_clear_sticky_form('register');

$form_vars = array_merge($form_vars, $sticky);

$content = elgg_view_form('register', [
	'class' => 'elgg-form-account post-form',
], $form_vars);

$content .= elgg_view('help/register');

if (elgg_is_xhr()) {
	echo $content;
	return;
}

if (elgg_is_active_plugin('hypeTheme')) {
	$shell = 'walled_garden';
} else {
	$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';
}

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'sidebar' => false,
]);

echo elgg_view_page($title, $body, $shell, [
	'class' => 'elgg-page-register',
]);
