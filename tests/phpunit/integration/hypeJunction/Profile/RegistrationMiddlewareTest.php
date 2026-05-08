<?php

namespace hypeJunction\Profile;

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\IntegrationTestCase;

class RegistrationMiddlewareTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypeprofile';
	}

	public function up(): void {
	}

	public function down(): void {
	}

	/**
	 * Regression: RegistrationMiddleware.php used \Elgg\BadRequestException (3.x FQN).
	 * After the fix it imports \Elgg\Exceptions\Http\BadRequestException.
	 * This test file fails to load entirely if the import FQN were wrong.
	 */
	public function testBadRequestExceptionFqnIsCorrect(): void {
		$e = new BadRequestException('test');
		$this->assertInstanceOf(BadRequestException::class, $e);
		$this->assertSame('test', $e->getMessage());
	}

	public function testRegistrationMiddlewareClassIsLoadable(): void {
		$this->assertTrue(class_exists(RegistrationMiddleware::class));
	}

	public function testGenerateUsernameReturnsLowercaseAlphanumeric(): void {
		$middleware = new RegistrationMiddleware();
		$username = $middleware->generateUsername('Jane Doe');
		$this->assertIsString($username);
		$this->assertNotEmpty($username);
		$this->assertMatchesRegularExpression('/^[a-z0-9.]+$/', $username);
	}
}
