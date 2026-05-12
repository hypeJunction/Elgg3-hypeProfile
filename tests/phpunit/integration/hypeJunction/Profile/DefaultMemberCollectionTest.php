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

	/**
     * @return string
     */
    public function getPluginID(): string {
		return 'hypeprofile';
	}

	/**
     * @return void
     */
    public function up(): void {
	}

	/**
     * @return void
     */
    public function down(): void {
	}

	/**
     * @return void
     */
    public function testCollectionExposesUserType(): void {
		$collection = new DefaultMemberCollection();
		$this->assertSame('user', $collection->getType());
		$this->assertSame('all', $collection->getCollectionType());
		$this->assertSame('collection:user:user', $collection->getId());
		$this->assertNull($collection->getSubtypes());
	}

	/**
     * @return void
     */
    public function testCollectionUrlMatchesRoute(): void {
		$collection = new DefaultMemberCollection();
		$expected = elgg_generate_url('collection:user:user');
		$this->assertSame($expected, $collection->getURL());
	}

	/**
     * @return void
     */
    public function testCollectionExposesExpectedSorters(): void {
		$collection = new DefaultMemberCollection();
		$this->assertSame(
			[Alpha::class, TimeCreated::class, FriendCount::class],
			$collection->getSortOptions()
		);
	}

	/**
     * @return void
     */
    public function testCollectionExposesExpectedFilters(): void {
		$collection = new DefaultMemberCollection();
		$this->assertSame(
			[All::class, IsOnline::class, IsFriend::class, IsNotFriend::class],
			$collection->getFilterOptions()
		);
	}

	/**
     * @return void
     */
    public function testCollectionExposesProfileSearchField(): void {
		$collection = new DefaultMemberCollection();
		$this->assertContains(ProfileDataSearchField::class, $collection->getSearchOptions());
	}

	/**
     * @return void
     */
    public function testCollectionMenuIsEmpty(): void {
		$this->assertSame([], (new DefaultMemberCollection())->getMenu());
	}
}
