<?php
/**
 * Settings
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package Emoji
 * @author  WPPunk
 */

namespace Emoji;

/**
 * Class Settings
 *
 * @package Emoji
 */
class Settings {

	/**
	 * Settings.
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		$this->settings = (array) get_option( 'emoji', [] );
	}

	/**
	 * Init hooks
	 *
	 * @since {VERSION}
	 */
	public function hooks() {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
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
		if ( apply_filters( 'emoji_add_menu', true ) ) {
			return;
		}

		add_menu_page(
			'Emoji Settings',
			'Emoji',
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
	 * Need to add emoji after content?
	 *
	 * @return bool
	 */
	public function emoji_after_content() {
		return ! empty( $this->settings['after_content'] );
	}

	/**
	 * Are styles enabled?
	 *
	 * @return bool
	 */
	public function is_styles_enabled() {
		return empty( $this->settings['disable_styles'] );
	}

	/**
	 * Are scripts enabled?
	 *
	 * @return bool
	 */
	public function is_scripts_enabled() {
		return empty( $this->settings['disable_scripts'] );
	}

	/**
	 * Plugin page callback.
	 *
	 * @since {VERSION}
	 */
	public function page_options() {
		$settings = $this->settings;
		$query    = new \WP_Query(
			[
				'post_type'      => 'post',
				'posts_per_page' => 1,
			]
		);
		$post_id  = $query->have_posts() ? $query->posts[0]->ID : 0;

		require_once plugin_dir_path( __DIR__ ) . 'templates/admin/settings.php';
	}

}
