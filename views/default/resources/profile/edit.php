<?php

$request = elgg_extract('request', $vars);
/* @var $request \Elgg\Request */

$username = elgg_extract('username', $vars);
$entity = get_user_by_username($username);

if (!$entity instanceof \ElggEntity) {
	throw new \Elgg\BadRequestException();
}

if (!$entity->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_entity_breadcrumbs($entity);

$subtype = $entity->getSubtype();


$svc = elgg()->{'posts.model'};
/* @var $svc \hypeJunction\Post\Model */

$vars = $request->getParams();
$vars['context'] = \hypeJunction\Fields\Field::CONTEXT_EDIT_FORM;

$form_vars = $svc->getFormVars($entity, $vars);

$content = elgg_view_form('post/save', [
	'class' => 'post-form',
	'action' => elgg_generate_action_url('profile/edit'),
], $form_vars);

if (elgg_is_xhr()) {
	echo $content;
	return;
}

$layout = elgg_view_layout('default', [
	'header' => false,
	'content' => $content,
	'sidebar' => false,
	'filter' => $action,
	'target' => $entity,
]);

echo elgg_view_page($entity->getDisplayName(), $layout);
