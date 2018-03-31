<?php

$entity = new ElggUser();

$svc = elgg()->{'posts.model'};
/* @var $svc \hypeJunction\Post\Model */

$fields = $svc->getFields($entity);

$fields = $fields->filter(function(\hypeJunction\Fields\FieldInterface $field) {
	return (bool) $field->is_search_field;
});

foreach ($fields as $field) {
	/* @var $field \hypeJunction\Fields\FieldInterface */

	$field->{'#label'} = $field->label($entity);
	$field->name = "profile[$field->name]";

	echo $field->render($entity);
}