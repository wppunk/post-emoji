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
	 * Settings
	 *
	 * @var \Emoji\Settings
	 */
	private $settings;

	/**
	 * Admin constructor.
	 *
	 * @param \Emoji\Emoji    $emoji    Emoji.
	 * @param \Emoji\Settings $settings Settings.
	 */
	public function __construct( $emoji, $settings ) {
		$this->emoji    = $emoji;
		$this->settings = $settings;
	}

	/**
	 * Register shortcode.
	 */
	public function register() {
		add_shortcode( 'emoji', [ $this, 'view' ] );
	}

	/**
	 * Shortcode view
	 *
	 * @param array $attr List of shortcode attributes.
	 *
	 * @return string
	 */
	public function view( $attr ) {
		$post_id       = $this->get_shortcode_post_id( $attr );
		$emoji         = $this->emoji->get( $post_id );
		$user_emotion  = $this->emoji->user_emotion( $post_id );
		$template_path = plugin_dir_path( __DIR__ ) . 'templates/shortcode.php';

		if ( (bool) apply_filters( 'emoji_styles', $this->settings->is_styles_enabled(), $post_id, $emoji ) ) {
			wp_enqueue_style( Plugin::SLUG );
			do_action( 'emoji_styles_loaded', $post_id, $attr, $emoji );
		}
		if ( (bool) apply_filters( 'emoji_scripts', $this->settings->is_scripts_enabled(), $post_id, $emoji ) ) {
			wp_enqueue_script( Plugin::SLUG );
			do_action( 'emoji_scripts_loaded', $post_id, $attr, $emoji );
		}

		$template_path = (string) apply_filters( 'emoji_shortcode_template', $template_path, $post_id, $attr, $emoji );
		ob_start();
		require $template_path;

		return ob_get_clean();
	}

	/**
	 * Shortcode view
	 *
	 * @param array $attr List of shortcode attributes.
	 *
	 * @return int
	 */
	private function get_shortcode_post_id( $attr ) {
		global $post;
		$post_id = ! empty( $attr['post_id'] ) ? absint( $attr['post_id'] ) : 0;
		if ( $post_id ) {
			return $post_id;
		}

		return $post ? absint( $post->ID ) : 0;
	}

}
