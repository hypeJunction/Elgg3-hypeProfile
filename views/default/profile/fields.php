<?php
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$svc = elgg()->{'posts.model'};
/* @var $svc \hypeJunction\Post\Model */

$fields = $svc->getFields($entity, \hypeJunction\Fields\Field::CONTEXT_PROFILE);
$fields = $fields->filter(function(\hypeJunction\Fields\FieldInterface $field) {
	return !in_array($field->name, ['description', 'briefdescription']);
});

$output = '';

$output .= elgg_view('output/longtext', [
	'value' => $entity->description,
	'class' => 'profile-description',
]);

foreach ($fields as $field) {
	/* @var $field \hypeJunction\Fields\FieldInterface */
	$output .= $field->output($entity);
}

if (empty($output)) {
	if ($entity->canEdit()) {
		$edit = elgg_view('output/url', [
			'href' => elgg_generate_entity_url($entity, 'edit'),
			'text' => elgg_echo("edit:$entity->type:$entity->subtype"),
			'icon_alt' => 'chevron-right',
		]);
		$message = elgg_echo('profile:empty', [$edit]);

		echo elgg_view_message('notice', $message, [
			'title' => false,
		]);
	}
} else {
	echo elgg_format_element('div', ['class' => 'elgg-profile-fields'], $output);
}