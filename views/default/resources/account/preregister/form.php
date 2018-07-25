<?php

$request = elgg_extract('request', $vars);
/* @var $request \Elgg\Request */

$content = elgg_view_form('account/preregister', [
	'class' => 'elgg-form-account post-form',
	'ajax' => true,
], $request->getParams());

if (elgg_is_active_plugin('hypeTheme')) {
	$shell = 'walled_garden';
} else {
	$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';
}

$title = elgg_echo('preregister');

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'sidebar' => false,
]);

echo elgg_view_page($title, $body, $shell, [
	'class' => 'elgg-page-register',
]);