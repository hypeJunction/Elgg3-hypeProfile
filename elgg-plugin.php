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
			'parsley.js' => $path . '/vendor/bower-asset/parsleyjs/dist/parsley.min.js',
			'zxcvbn/' => $path . '/vendor/bower-asset/zxcvbn/dist/',
		]
	],
	'actions' => [
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
		'validation/send_code' => [
			'access' => 'public',
			'controller' => \hypeJunction\Profile\SendVerificationCodeAction::class,
			'middleware' => [
				\Elgg\Router\Middleware\AjaxGatekeeper::class,
			],
		],
	],
	'routes' => [
		'collection:user:user' => [
			'path' => '/members',
			'resource' => 'collection/all',
		],
	],
	'settings' => [
		'email_validation' => true,
		'first_last_name' => true,
		'hide_password_repeat' => true,
	],
];