<?php

namespace hypeJunction\Profile;

use Elgg\IntegrationTestCase;
use hypeJunction\Lists\Sorters\Alpha;
use hypeJunction\Lists\Sorters\FriendCount;
use hypeJunction\Lists\Sorters\TimeCreated;
use hypeJunction\Lists\Filters\All;
use hypeJunction\Lists\Filters\IsFriend;
use hypeJunction\Lists\Filters\IsNotFriend;
use hypeJunction\Lists\Filters\IsOnline;

class DefaultMemberCollectionTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypeprofile';
	}

	public function up(): void {
	}

	public function down(): void {
	}

	public function testCollectionExposesUserType(): void {
		$collection = new DefaultMemberCollection();
		$this->assertSame('user', $collection->getType());
		$this->assertSame('all', $collection->getCollectionType());
		$this->assertSame('collection:user:user', $collection->getId());
		$this->assertNull($collection->getSubtypes());
	}

	public function testCollectionUrlMatchesRoute(): void {
		$collection = new DefaultMemberCollection();
		$expected = elgg_generate_url('collection:user:user');
		$this->assertSame($expected, $collection->getURL());
	}

	public function testCollectionExposesExpectedSorters(): void {
		$collection = new DefaultMemberCollection();
		$this->assertSame(
			[Alpha::class, TimeCreated::class, FriendCount::class],
			$collection->getSortOptions()
		);
	}

	public function testCollectionExposesExpectedFilters(): void {
		$collection = new DefaultMemberCollection();
		$this->assertSame(
			[All::class, IsOnline::class, IsFriend::class, IsNotFriend::class],
			$collection->getFilterOptions()
		);
	}

	public function testCollectionExposesProfileSearchField(): void {
		$collection = new DefaultMemberCollection();
		$this->assertContains(ProfileDataSearchField::class, $collection->getSearchOptions());
	}

	public function testCollectionMenuIsEmpty(): void {
		$this->assertSame([], (new DefaultMemberCollection())->getMenu());
	}
}
