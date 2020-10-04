<?php
/**
 * PluginTest
 *
 * @since   {VERSION}
 * @link    {URL}
 * @license GPLv2 or later
 * @package PluginName
 * @author  {AUTHOR}
 */

namespace EmojiTests;

use Mockery;
use Emoji\Plugin;

/**
 * Class FrontTest
 *
 * @since   {VERSION}
 *
 * @package PluginNameUnitTests\Front
 */
class PluginTest extends TestCase {

	/**
	 * Test run
	 *
	 * @throws \Exception Invalid service name.
	 */
	public function test_run() {
		$front = Mockery::mock( '\Emoji\Front' );
		$front
			->shouldReceive( 'hooks' )
			->withNoArgs()
			->once();
		$admin = Mockery::mock( '\Emoji\Admin' );
		$admin
			->shouldReceive( 'hooks' )
			->withNoArgs()
			->once();
		$shortcode = Mockery::mock( '\Emoji\Shortcode' );
		$shortcode
			->shouldReceive( 'register' )
			->withNoArgs()
			->once();
		$container_builder = Mockery::mock( '\Emoji\Vendor\Symfony\Component\DependencyInjection\ContainerBuilder' );
		$container_builder
			->shouldReceive( 'get' )
			->with( 'front' )
			->once()
			->andReturn( $front );
		$container_builder
			->shouldReceive( 'get' )
			->with( 'admin' )
			->once()
			->andReturn( $admin );
		$container_builder
			->shouldReceive( 'get' )
			->with( 'shortcode' )
			->once()
			->andReturn( $shortcode );
		$plugin = new Plugin( $container_builder );

		$plugin->run();
	}

}
