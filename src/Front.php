<?php
/**
 * Frontend area
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package Emoji
 * @author  WPPunk
 */

namespace Emoji;

/**
 * Class Front
 *
 * @package Emoji
 */
class Front {

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
	 * Front constructor.
	 *
	 * @param \Emoji\Emoji    $emoji    Emoji.
	 * @param \Emoji\Settings $settings Settings.
	 */
	public function __construct( $emoji, $settings ) {
		$this->emoji    = $emoji;
		$this->settings = $settings;
	}

	/**
	 * Load hooks
	 */
	public function hooks() {
		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_emotion', [ $this, 'ajax' ] );
			add_action( 'wp_ajax_nopriv_emotion', [ $this, 'ajax' ] );

			return;
		}
		add_action( 'wp_enqueue_scripts', [ $this, 'styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_filter( 'the_content', [ $this, 'emoji_after_content' ] );
	}

	/**
	 * Add emoji after content.
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function emoji_after_content( $content ) {
		if ( is_feed() ) {
			return $content;
		}
		if ( ! is_singular( 'post' ) ) {
			return $content;
		}

		return $this->settings->emoji_after_content() ? $content . do_shortcode( '[emoji]' ) : $content;
	}

	/**
	 * Load styles
	 */
	public function styles() {
		wp_register_style(
			Plugin::SLUG,
			plugin_dir_url( __DIR__ ) . 'assets/build/css/main.css',
			[],
			Plugin::VERSION
		);
		do_action( 'emoji_styles_registered' );
	}

	/**
	 * Load scripts
	 */
	public function scripts() {
		wp_register_script(
			Plugin::SLUG,
			plugin_dir_url( __DIR__ ) . '/assets/build/js/main.js',
			[],
			Plugin::VERSION,
			true
		);
		wp_localize_script(
			Plugin::SLUG,
			Plugin::SLUG,
			(array) apply_filters(
				'emoji_localize_arguments',
				[
					'nonce'     => wp_create_nonce( Plugin::SLUG ),
					'url'       => admin_url( 'admin-ajax.php' ),
					'post_id'   => get_the_ID(),
					'audio_dir' => EMOJI_URL . 'assets/build/audio/',
				]
			)
		);
		do_action( 'emoji_scripts_registered' );
	}

	/**
	 * Ajax handler
	 */
	public function ajax() {
		check_ajax_referer( Plugin::SLUG, 'nonce' );
		$post_id      = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
		$user_emotion = filter_input( INPUT_POST, 'emotion', FILTER_SANITIZE_STRING );
		if ( ! $post_id ) {
			wp_send_json_error( null, 400 );

			return;
		}

		$response = (array) apply_filters(
			'emoji_ajax_response',
			[
				'active' => $user_emotion && $this->emoji->vote( $post_id, $user_emotion ) ? $user_emotion : $this->emoji->user_emotion( $post_id ),
				'emoji'  => $this->emoji->get( $post_id ),
			],
			$user_emotion,
			$post_id
		);

		wp_send_json_success( $response );
	}

}
