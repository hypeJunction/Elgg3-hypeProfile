<?php

namespace hypeJunction\Profile;

use Elgg\IntegrationTestCase;
use hypeJunction\ValidationException;
use Symfony\Component\HttpFoundation\ParameterBag;

class FullNameFieldTest extends IntegrationTestCase {

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
    public function testValidateRejectsMissingFirstName(): void {
		$field = new FullNameField(['type' => 'profile/full_name', 'name' => 'name']);
		$this->expectException(ValidationException::class);
		$field->validate(['first_name' => '', 'last_name' => 'Doe']);
	}

	/**
     * @return void
     */
    public function testValidateRejectsMissingLastName(): void {
		$field = new FullNameField(['type' => 'profile/full_name', 'name' => 'name']);
		$this->expectException(ValidationException::class);
		$field->validate(['first_name' => 'Jane', 'last_name' => '']);
	}

	/**
     * @return void
     */
    public function testValidateAcceptsBothNames(): void {
		$field = new FullNameField(['type' => 'profile/full_name', 'name' => 'name']);
		$this->assertTrue($field->validate(['first_name' => 'Jane', 'last_name' => 'Doe']));
	}

	/**
     * @return void
     */
    public function testRetrieveReadsFirstAndLastName(): void {
		$user = $this->createUser();
		$user->first_name = 'Jane';
		$user->last_name = 'Doe';
		$user->save();

		$field = new FullNameField(['type' => 'profile/full_name', 'name' => 'name']);
		$value = $field->retrieve($user);
		$this->assertSame(['first_name' => 'Jane', 'last_name' => 'Doe'], $value);
	}

	/**
     * @return void
     */
    public function testRetrieveSplitsDisplayNameWhenComponentsMissing(): void {
		// createUser() seeds first_name/last_name via Faker; clear them so the
		// fall-through "split name on space" branch is exercised.
		$user = $this->createUser();
		unset($user->first_name);
		unset($user->last_name);
		$user->name = 'Alice Wonderland';
		$user->save();

		$field = new FullNameField(['type' => 'profile/full_name', 'name' => 'name']);
		$value = $field->retrieve($user);
		$this->assertSame(['first_name' => 'Alice', 'last_name' => 'Wonderland'], $value);
	}

	/**
     * @return void
     */
    public function testSaveWritesNameComponentsAndAbbreviates(): void {
		$user = $this->createUser();

		$field = new FullNameField(['type' => 'profile/full_name', 'name' => 'name']);
		$params = new ParameterBag([
			'name' => ['first_name' => 'jane', 'last_name' => 'doe'],
		]);
		$field->save($user, $params);

		$this->assertSame('Jane', $user->first_name);
		$this->assertSame('Doe', $user->last_name);
		$this->assertSame('D.', $user->last_name_abbr);
		$this->assertSame('Jane D.', $user->name);
	}

	/**
     * @return void
     */
    public function testSaveTruncatesDisplayNameToFiftyChars(): void {
		$user = $this->createUser();

		$field = new FullNameField(['type' => 'profile/full_name', 'name' => 'name']);
		$first = str_repeat('a', 60);
		$params = new ParameterBag([
			'name' => ['first_name' => $first, 'last_name' => 'b'],
		]);
		$field->save($user, $params);

		$this->assertLessThanOrEqual(50, strlen($user->name));
	}
}
