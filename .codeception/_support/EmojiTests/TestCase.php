<?php
/**
 * TestCase for Unit tests
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package Emoji
 * @author  WPPunk
 */

namespace EmojiTests;

use Mockery;

use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

/**
 * Class TestCase
 *
 * @since   1.0.0
 *
 * @package PluginNameTests
 */
abstract class TestCase extends \Codeception\PHPUnit\TestCase {

	/**
	 * This method is called before each test.
	 *
	 * @since   1.0.0
	 */
	protected function setUp(): void {
		parent::setUp();
		setUp();
	}

	/**
	 * This method is called after each test.
	 *
	 * @since   1.0.0
	 */
	protected function tearDown(): void {
		tearDown();
		Mockery::close();
		parent::tearDown();
	}

}
