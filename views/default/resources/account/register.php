<?php

if (elgg_is_logged_in()) {
	$user = elgg_get_logged_in_user_entity();
	$exception = new \Elgg\GatekeeperException();
	$exception->setRedirectUrl($user->getURL());
	throw new $exception;
}

if (elgg_get_config('allow_registration') == false) {
	throw new \Elgg\GatekeeperException(elgg_echo('registerdisabled'));
}

$request = elgg_extract('request', $vars);
/* @var $request \Elgg\Request */

$entity = new ElggUser();

$svc = elgg()->{'posts.model'};
/* @var $svc \hypeJunction\Post\Model */

$form_vars = $svc->getFormVars($entity, $request->getParams());

$sticky = elgg_get_sticky_values('register');
elgg_clear_sticky_form('register');

$form_vars = array_merge($form_vars, $sticky);

$content = elgg_view_form('register', [
	'class' => 'elgg-form-account post-form',
], $form_vars);

$content .= elgg_view('help/register');

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

$title = elgg_echo('register');

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'sidebar' => false,
]);

echo elgg_view_page($title, $body, $shell, [
	'class' => 'elgg-page-register',
]);
