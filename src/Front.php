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
		add_action( 'wp_enqueue_scripts', [ $this, 'styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_filter( 'the_content', [ $this, 'emoji_after_content' ] );
		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_emotion', [ $this, 'ajax' ] );
			add_action( 'wp_ajax_nopriv_emotion', [ $this, 'ajax' ] );
		}
	}

	/**
	 * Add emoji after content.
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function emoji_after_content( $content ) {
		return $this->settings->emoji_after_content() ? $content . do_shortcode( '[emoji]' ) : $content;
	}

	/**
	 * Load styles
	 */
	public function styles() {
		if ( ! is_single() || ! (bool) apply_filters( 'emoji_styles', $this->settings->is_styles_enabled() ) ) {
			return;
		}
		wp_register_style(
			Plugin::SLUG,
			plugin_dir_url( __DIR__ ) . '/assets/build/css/main.css',
			[],
			Plugin::VERSION
		);
		do_action( 'emoji_styles_registered' );
	}

	/**
	 * Load scripts
	 */
	public function scripts() {
		if ( ! is_single() || ! (bool) apply_filters( 'emoji_scripts', $this->settings->is_scripts_enabled() ) ) {
			return;
		}
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
					'nonce'   => wp_create_nonce( Plugin::SLUG ),
					'url'     => admin_url( 'admin-ajax.php' ),
					'post_id' => get_the_ID(),
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
		if ( ! $post_id || ! $user_emotion ) {
			wp_send_json_error();
		}
		$previous_user_emotion = $this->emoji->user_emotion( $post_id );
		$this->emoji->vote( $post_id, $user_emotion, $previous_user_emotion );
		$response['emoji'] = $this->emoji->get( $post_id );
		if ( $user_emotion !== $previous_user_emotion ) {
			$response['active'] = $user_emotion;
		}
		wp_send_json_success(
			(array) apply_filters(
				'emoji_ajax_response',
				$response,
				$user_emotion,
				$post_id
			)
		);
	}

}
