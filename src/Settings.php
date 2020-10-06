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
	 * Get settings
	 *
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
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

}
