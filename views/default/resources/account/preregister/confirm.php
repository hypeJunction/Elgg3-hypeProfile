<?php

$request = elgg_extract('request', $vars);
/* @var $request \Elgg\Request */

$address = $request->getParam('email');
if (!$address) {
	throw new \Elgg\BadRequestException();
}

$content = elgg_view('output/longtext', [
	'value' => elgg_echo('preregister:confirm:desc', [$address]),
]);

if (elgg_is_active_plugin('hypeTheme')) {
	$shell = 'walled_garden';
} else {
	$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';
}

$title = elgg_echo('preregister:confirm');

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'sidebar' => false,
]);

echo elgg_view_page($title, $body, $shell, [
	'class' => 'elgg-page-register',
]);