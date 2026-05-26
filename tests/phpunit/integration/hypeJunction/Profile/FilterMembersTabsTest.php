<?php

namespace hypeJunction\Profile;

use Elgg\Collections\Collection;
use Elgg\HooksRegistrationService\Hook;
use Elgg\IntegrationTestCase;

class FilterMembersTabsTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypeprofile';
	}

	public function up(): void {
	}

	public function down(): void {
	}

	public function testHandlerRemovesDefaultTabsAndAddsAll(): void {
		$tabs = new Collection();
		foreach (['alpha', 'newest', 'popular', 'online', 'unrelated'] as $name) {
			$tabs->add(\ElggMenuItem::factory([
				'name' => $name,
				'text' => $name,
				'href' => '#',
			]));
		}

		$hook = $this->getMockBuilder(Hook::class)
			->disableOriginalConstructor()
			->getMock();
		$hook->method('getValue')->willReturn($tabs);

		$result = (new FilterMembersTabs())($hook);

		$this->assertInstanceOf(Collection::class, $result);

		$names = [];
		foreach ($result as $item) {
			$names[] = $item->getName();
		}

		$this->assertNotContains('alpha', $names);
		$this->assertNotContains('newest', $names);
		$this->assertNotContains('popular', $names);
		$this->assertNotContains('online', $names);
		$this->assertContains('unrelated', $names, 'tabs unrelated to the filter must be left in place');
		$this->assertContains('all', $names);
	}

	public function testAllTabHrefMatchesGeneratedRoute(): void {
		$tabs = new Collection();

		$hook = $this->getMockBuilder(Hook::class)
			->disableOriginalConstructor()
			->getMock();
		$hook->method('getValue')->willReturn($tabs);

		$result = (new FilterMembersTabs())($hook);

		$all = null;
		foreach ($result as $item) {
			if ($item->getName() === 'all') {
				$all = $item;
				break;
			}
		}

		$this->assertInstanceOf(\ElggMenuItem::class, $all);
		$this->assertSame(\elgg_generate_url('collection:user:user'), $all->getHref());
	}
}
