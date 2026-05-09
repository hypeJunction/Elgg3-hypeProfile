<?php

namespace hypeJunction\Profile;

use Elgg\Event;
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
		$event = $this->getMockBuilder(Event::class)->disableOriginalConstructor()->getMock();
		$event->method('getParam')->willReturnCallback(function ($key) {
			return $key === 'email' ? 'invitee@example.com' : null;
		});
		$event->method('getValue')->willReturn(['existing' => 'param']);

		$result = (new AddValidationTokenTInviteUrl())($event);

		$this->assertIsArray($result);
		$this->assertArrayHasKey('ev', $result);
		$this->assertSame(10, strlen($result['ev']));
		$this->assertSame('param', $result['existing'], 'pre-existing params must be preserved');
	}

	public function testTokenIsDeterministicPerEmail(): void {
		$buildResult = function (string $email): string {
			$event = $this->getMockBuilder(Event::class)->disableOriginalConstructor()->getMock();
			$event->method('getParam')->willReturnCallback(fn($k) => $k === 'email' ? $email : null);
			$event->method('getValue')->willReturn([]);
			$out = (new AddValidationTokenTInviteUrl())($event);
			return $out['ev'];
		};

		$this->assertSame($buildResult('a@example.com'), $buildResult('a@example.com'));
		$this->assertNotSame($buildResult('a@example.com'), $buildResult('b@example.com'));
	}
}
