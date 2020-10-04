<?php
/**
 * Shortcode
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package Emoji
 * @author  WPPunk
 */

namespace Emoji;

/**
 * Class Shortcode
 *
 * @package Emoji
 */
class Shortcode {

	/**
	 * Emoji
	 *
	 * @var \Emoji\Emoji
	 */
	private $emoji;

	/**
	 * Admin constructor.
	 *
	 * @param \Emoji\Emoji $emoji Emoji.
	 */
	public function __construct( $emoji ) {
		$this->emoji = $emoji;
	}

	/**
	 * Register shortcode.
	 */
	public function register() {
		add_shortcode( 'emoji', [ $this, 'view' ] );
	}

	/**
	 * Shortcode view
	 */
	public function view() {
		global $post;
		$emoji        = apply_filters( 'emoji_view_list', $this->emoji->get( $post->ID ) );
		$user_emotion = $this->emoji->user_emotion( $post->ID );

		if ( ! (bool) apply_filters( 'emoji_skip_styles', false ) ) {
			wp_enqueue_style( Plugin::SLUG );
			do_action( 'emoji_styles_loaded' );
		}
		if ( ! (bool) apply_filters( 'emoji_skip_scripts', false ) ) {
			wp_enqueue_script( Plugin::SLUG );
			do_action( 'emoji_scripts_loaded' );
		}

		require plugin_dir_path( __DIR__ ) . 'templates/shortcode.php';
	}

}
