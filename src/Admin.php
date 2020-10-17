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
	 * Load hooks.
	 */
	public function hooks() {
		add_filter( 'manage_post_posts_columns', [ $this, 'register_columns' ] );
		add_action( 'manage_post_posts_custom_column', [ $this, 'manage_columns' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Register styles.
	 */
	public function styles() {
		$screen = get_current_screen();
		if ( ! $screen || 'toplevel_page_emoji' !== $screen->base ) {
			return;
		}

		wp_enqueue_style(
			Plugin::SLUG . '-admin',
			plugin_dir_url( __DIR__ ) . '/assets/build/css/admin.css',
			[],
			Plugin::VERSION
		);
		wp_register_style(
			Plugin::SLUG,
			plugin_dir_url( __DIR__ ) . '/assets/build/css/main.css',
			[],
			Plugin::VERSION
		);
		do_action( 'emoji_styles_registered' );
	}

	/**
	 * Register scripts.
	 */
	public function scripts() {
		$screen = get_current_screen();
		if ( ! $screen || 'toplevel_page_emoji' !== $screen->base ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script(
			Plugin::SLUG . '-admin',
			plugin_dir_url( __DIR__ ) . '/assets/build/js/admin.js',
			[ 'jquery', 'jquery-ui-sortable' ],
			Plugin::VERSION,
			true
		);
	}

	/**
	 * Register emoji column in the post list table.
	 *
	 * @param array $columns List of columns.
	 *
	 * @return array
	 */
	public function register_columns( $columns ) {
		$columns['emoji'] = esc_html__( 'Emoji', 'post-emoji' );

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

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting( Plugin::SLUG, Plugin::SLUG );
	}

	/**
	 * Add plugin page in WordPress menu.
	 *
	 * @since {VERSION}
	 */
	public function add_menu() {
		if ( ! apply_filters( 'emoji_add_menu', true ) ) {
			return;
		}

		add_menu_page(
			esc_html__( 'Emoji Settings', 'post-emoji' ),
			esc_html__( 'Emoji', 'post-emoji' ),
			'manage_options',
			Plugin::SLUG,
			[
				$this,
				'page_options',
			],
			'dashicons-smiley'
		);
	}

	/**
	 * Plugin page callback.
	 *
	 * @since {VERSION}
	 */
	public function page_options() {
		$settings = $this->settings->get_settings();
		$query    = new \WP_Query(
			[
				'post_type'      => 'post',
				'posts_per_page' => 1,
			]
		);
		$post_id  = $query->have_posts() ? $query->posts[0]->ID : 0;
		$emoji    = $this->settings->get_emoji();

		require_once plugin_dir_path( __DIR__ ) . 'templates/admin/settings.php';
	}

}
