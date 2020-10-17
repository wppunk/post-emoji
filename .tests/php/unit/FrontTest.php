<?php
/**
 * FrontTest
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package PluginName
 * @author  WPPunk
 */

namespace EmojiTests;

use Mockery;
use Emoji\Front;
use Emoji\Plugin;
use tad\FunctionMocker\FunctionMocker;

/**
 * Class FrontTest
 *
 * @since   1.0.0
 *
 * @package PluginNameUnitTests\Front
 */
class FrontTest extends TestCase {

	/**
	 * SetUp
	 */
	protected function setUp(): void {
		parent::setUp();
		remove_filter( 'wp_doing_ajax', '__return_true' );
		$_REQUEST             = [];
		$_SERVER['HTTP_HOST'] = '';
	}

	/**
	 * Test styles
	 */
	public function test_styles() {
		$front = new Front( Mockery::mock( '\Emoji\Emoji' ), Mockery::mock( '\Emoji\Settings' ) );
		global $wp_query;
		$wp_query->is_single = true;

		$front->styles();

		global $wp_styles;
		$this->assertNotEmpty( $wp_styles->registered['emoji'] );
		$this->assertSame( '/wp-content/plugins/post-emoji/assets/build/css/main.css', $wp_styles->registered['emoji']->src );
		$this->assertSame( Plugin::VERSION, $wp_styles->registered['emoji']->ver );
		$this->assertSame( 1, did_action( 'emoji_styles_registered' ) );
	}

	/**
	 * Test hooks
	 */
	public function test_hooks() {
		$front = new Front( Mockery::mock( '\Emoji\Emoji' ), Mockery::mock( '\Emoji\Settings' ) );

		$front->hooks();

		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', [ $front, 'styles' ] ) );
		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', [ $front, 'scripts' ] ) );
		$this->assertSame( 10, has_filter( 'the_content', [ $front, 'emoji_after_content' ] ) );
	}

	/**
	 * Test ajax hooks
	 */
	public function test_ajax_hooks() {
		add_filter( 'wp_doing_ajax', '__return_true' );
		$front = new Front( Mockery::mock( '\Emoji\Emoji' ), Mockery::mock( '\Emoji\Settings' ) );

		$front->hooks();

		$this->assertSame( 10, has_action( 'wp_ajax_emotion', [ $front, 'ajax' ] ) );
		$this->assertSame( 10, has_action( 'wp_ajax_nopriv_emotion', [ $front, 'ajax' ] ) );
	}

	/**
	 * Test without emoji after content
	 */
	public function test_WITHOUT_emoji_after_content() {
		$settings = Mockery::mock( '\Emoji\Settings' );
		$settings
			->shouldReceive( 'emoji_after_content' )
			->once()
			->andReturnFalse();
		$front = new Front( Mockery::mock( '\Emoji\Emoji' ), $settings );

		$this->assertEmpty( $front->emoji_after_content( '' ) );
	}

	/**
	 * Test emoji after content
	 */
	public function test_emoji_after_content() {
		$settings = Mockery::mock( '\Emoji\Settings' );
		$settings
			->shouldReceive( 'emoji_after_content' )
			->once()
			->andReturnTrue();
		$front = new Front( Mockery::mock( '\Emoji\Emoji' ), $settings );

		$this->assertNotEmpty( $front->emoji_after_content( '' ) );
	}

	/**
	 * Test fail ajax
	 */
	public function test_FAIL_ajax() {
		add_filter( 'wp_doing_ajax', '__return_true' );
		$_REQUEST['nonce'] = wp_create_nonce( Plugin::SLUG );
		$front             = new Front( Mockery::mock( '\Emoji\Emoji' ), Mockery::mock( '\Emoji\Settings' ) );

		$front->ajax();

		$this->assertSame(
			wp_json_encode(
				[
					'success' => false,
				]
			),
			ob_get_clean()
		);
	}

	/**
	 * Test ajax
	 */
	public function test_ajax() {
		add_filter( 'wp_doing_ajax', '__return_true' );
		$_REQUEST['nonce']   = wp_create_nonce( Plugin::SLUG );
		$post_id             = 10;
		$user_emotion        = 'awesome';
		$result              = [
			'awesome' => 1,
			'happy'   => 1,
		];
		$filter_input_values = [ $post_id, $user_emotion ];
		FunctionMocker::replace(
			'filter_input',
			function () use ( $filter_input_values ) {
				static $i = 0;

				return $filter_input_values[ $i ++ ];
			}
		);
		$emoji = Mockery::mock( '\Emoji\Emoji' );
		$emoji
			->shouldReceive( 'vote' )
			->with( $post_id, $user_emotion )
			->once()
			->andReturnTrue();
		$emoji
			->shouldReceive( 'get' )
			->with( $post_id )
			->once()
			->andReturn( $result );
		$front = new Front( $emoji, Mockery::mock( '\Emoji\Settings' ) );

		$front->ajax();

		$this->assertSame(
			wp_json_encode(
				[
					'success' => true,
					'data'    => [
						'active' => $user_emotion,
						'emoji'  => $result,
					],
				]
			),
			ob_get_clean()
		);
	}

}
