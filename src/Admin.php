<?php
/**
 * Admin area
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package Emoji
 * @author  WPPunk
 */

namespace Emoji;

/**
 * Class Admin
 *
 * @package Emoji
 */
class Admin {

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
	 * Load hooks.
	 */
	public function hooks() {
		add_filter( 'manage_post_posts_columns', [ $this, 'register_columns' ] );
		add_action( 'manage_post_posts_custom_column', [ $this, 'manage_columns' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'styles' ] );
	}

	/**
	 * Register styles.
	 */
	public function styles() {
		$screen = get_current_screen();
		if ( ! $screen || 'toplevel_page_emoji' !== $screen->base ) {
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
	 * Register emoji column in the post list table.
	 *
	 * @param array $columns List of columns.
	 *
	 * @return array
	 */
	public function register_columns( $columns ) {
		$columns['emoji'] = esc_html__( 'Emoji', 'emoji' );

		return $columns;
	}

	/**
	 * Show count of emoji for the post.
	 *
	 * @param string $column_name Column name.
	 */
	public function manage_columns( $column_name ) {
		if ( 'emoji' === $column_name ) {
			echo absint( array_sum( $this->emoji->get( get_the_ID() ) ) );
		}
	}

}
