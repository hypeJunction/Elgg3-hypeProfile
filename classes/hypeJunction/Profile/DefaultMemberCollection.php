<?php

namespace hypeJunction\Profile;

use hypeJunction\Lists\Collection;
use hypeJunction\Lists\Filters\All;
use hypeJunction\Lists\Filters\IsAdministeredBy;
use hypeJunction\Lists\Filters\IsFriend;
use hypeJunction\Lists\Filters\IsMember;
use hypeJunction\Lists\Filters\IsNotFriend;
use hypeJunction\Lists\Filters\IsOnline;
use hypeJunction\Lists\Sorters\Alpha;
use hypeJunction\Lists\Sorters\FriendCount;
use hypeJunction\Lists\Sorters\MemberCount;
use hypeJunction\Lists\Sorters\TimeCreated;

class DefaultMemberCollection extends Collection {

	/**
	 * Get ID of the collection
	 * @return string
	 */
	public function getId() {
		return "collection:user:user";
	}

	/**
	 * Get title of the collection
	 * @return string
	 */
	public function getDisplayName() {
		return elgg_echo("collection:user:user");
	}

	/**
	 * Get the type of collection, e.g. owner, friends, group all
	 * @return string
	 */
	public function getCollectionType() {
		return 'all';
	}

	/**
	 * Get type of entities in the collection
	 * @return mixed
	 */
	public function getType() {
		return 'user';
	}

	/**
	 * Get subtypes of entities in the collection
	 * @return string|string[]
	 */
	public function getSubtypes() {
		return null;
	}

	/**
	 * Get default query options
	 *
	 * @param array $options Query options
	 *
	 * @return array
	 */
	public function getQueryOptions(array $options = []) {
		return $options;
	}

	/**
	 * Get default list view options
	 *
	 * @param array $options List view options
	 *
	 * @return mixed
	 */
	public function getListOptions(array $options = []) {
		return array_merge([
			'no_results' => elgg_echo("collection:user:user:no_results"),
			'full_view' => false,
			'list_type' => 'list',
			'list_class' => 'elgg-users',
		], $options);
	}

	/**
	 * Returns base URL of the collection
	 *
	 * @return string
	 */
	public function getURL() {
		return elgg_generate_url($this->getId());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSearchOptions() {
		$options = parent::getSearchOptions();

		$options[] = ProfileDataSearchField::class;

		return $options;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSortOptions() {
		return [
			Alpha::id() => Alpha::class,
			TimeCreated::id() => TimeCreated::class,
			FriendCount::id() => FriendCount::class,
			IsOnline::id() => IsOnline::class,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFilterOptions() {
		return [
			All::id() => All::class,
			IsFriend::id() => IsFriend::class,
			IsNotFriend::id() => IsNotFriend::class,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMenu() {
		return [];
	}
}