<?php

namespace hypeJunction\Profile;

use Elgg\Hook;
use Elgg\IntegrationTestCase;

class AddValidationTokenTInviteUrlTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypeprofile';
	}

	public function up(): void {
	}

	public function down(): void {
	}

	public function testHandlerAppendsTenCharacterEvToken(): void {
		$hook = $this->getMockBuilder(Hook::class)->getMock();
		$hook->method('getParam')->willReturnCallback(function ($key) {
			return $key === 'email' ? 'invitee@example.com' : null;
		});
		$hook->method('getValue')->willReturn(['existing' => 'param']);

		$result = (new AddValidationTokenTInviteUrl())($hook);

		$this->assertIsArray($result);
		$this->assertArrayHasKey('ev', $result);
		$this->assertSame(10, strlen($result['ev']));
		$this->assertSame('param', $result['existing'], 'pre-existing params must be preserved');
	}

	public function testTokenIsDeterministicPerEmail(): void {
		$buildResult = function (string $email): string {
			$hook = $this->getMockBuilder(Hook::class)->getMock();
			$hook->method('getParam')->willReturnCallback(fn($k) => $k === 'email' ? $email : null);
			$hook->method('getValue')->willReturn([]);
			$out = (new AddValidationTokenTInviteUrl())($hook);
			return $out['ev'];
		};

		$this->assertSame($buildResult('a@example.com'), $buildResult('a@example.com'));
		$this->assertNotSame($buildResult('a@example.com'), $buildResult('b@example.com'));
	}
}
