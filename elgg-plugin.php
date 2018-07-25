<?php

$plugin_root = __DIR__;
$root = dirname(dirname($plugin_root));
$alt_root = dirname(dirname(dirname($root)));

if (file_exists("$plugin_root/vendor/autoload.php")) {
	$path = $plugin_root;
} else if (file_exists("$root/vendor/autoload.php")) {
	$path = $root;
} else {
	$path = $alt_root;
}

return [
	'bootstrap' => \hypeJunction\Profile\Bootstrap::class,
	'views' => [
		'default' => [
			'zxcvbn/' => $path . '/vendor/bower-asset/zxcvbn/dist/',
		]
	],
	'actions' => [
		'register' => [
			'controller' => \hypeJunction\Profile\RegisterAction::class,
			'access' => 'public',
		],
		'account/preregister' => [
			'controller' => \hypeJunction\Profile\PreRegisterAction::class,
			'access' => 'public',
		],
		'profile/edit' => [
			'controller' => \hypeJunction\Post\SavePostAction::class,
		],
		'validation/is_valid_username' => [
			'access' => 'public',
			'controller' => \hypeJunction\Profile\IsValidUsername::class,
		],
		'validation/is_available_username' => [
			'access' => 'public',
			'controller' => \hypeJunction\Profile\IsAvailableUsername::class,
		],
		'validation/is_available_email' => [
			'access' => 'public',
			'controller' => \hypeJunction\Profile\IsAvailableEmail::class,
		],
	],
	'routes' => [
		'account:register' => [
			'path' => '/register/{subtype?}',
			'resource' => 'account/register',
			'walled' => false,
			'middleware' => [
				\hypeJunction\Profile\RegistrationGatekeeper::class,
			],
		],
		'account:preregister' => [
			'path' => '/preregister/form/{subtype?}',
			'resource' => 'account/preregister/form',
			'walled' => false,
		],
		'account:preregister:confirm' => [
			'path' => '/preregister/confirm',
			'resource' => 'account/preregister/confirm',
			'walled' => false,
		],
		'collection:user:user' => [
			'path' => '/members',
			'resource' => 'collection/all',
		],
	],
	'settings' => [
		'email_validation' => true,
		'first_last_name' => true,
		'hide_password_repeat' => true,
		'field_access' => 'field_picker',
	],
];