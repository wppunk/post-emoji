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
	 * List of default emoji
	 *
	 * @var array
	 */
	private $default_emoji = [
		'cool'  => [
			'id'  => 0,
			'url' => EMOJI_URL . 'assets/build/img/cool.svg',
		],
		'happy' => [
			'id'  => 0,
			'url' => EMOJI_URL . 'assets/build/img/happy.svg',
		],
		'good'  => [
			'id'  => 0,
			'url' => EMOJI_URL . 'assets/build/img/good.svg',
		],
		'nerd'  => [
			'id'  => 0,
			'url' => EMOJI_URL . 'assets/build/img/nerd.svg',
		],
		'sad'   => [
			'id'  => 0,
			'url' => EMOJI_URL . 'assets/build/img/sad.svg',
		],
		'poop'  => [
			'id'  => 0,
			'url' => EMOJI_URL . 'assets/build/img/poop.svg',
		],
	];

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

	/**
	 * Get list of emoji.
	 *
	 * @return array
	 */
	public function get_emoji() {
		if ( empty( $this->settings['emoji'] ) ) {
			return $this->default_emoji;
		}

		$emoji = $this->sanitize_emotions();

		return $emoji ? $emoji : $this->default_emoji;
	}

	/**
	 * Sanitize emotions
	 *
	 * @return array
	 */
	private function sanitize_emotions() {
		$emoji = [];
		foreach ( $this->settings['emoji'] as $emotion ) {
			$emotion = $this->sanitize_emotion( $emotion );

			if ( empty( $emotion['name'] ) || empty( $emotion['url'] ) ) {
				continue;
			}
			$emoji[ $emotion['name'] ] = $emotion;
		}

		return $emoji;
	}

	/**
	 * Sanitize emotion name
	 *
	 * @param string $name Emotion name.
	 *
	 * @return string
	 */
	private function sanitize_emotion_name( $name ) {
		return preg_replace( '/[^a-z]+/', '', $name );
	}

	/**
	 * Sanitize emotion.
	 *
	 * @param array $emotion Emotion name, image id, url.
	 *
	 * @return array|mixed
	 */
	private function sanitize_emotion( $emotion ) {
		$emotion['name'] = $this->sanitize_emotion_name( $emotion['name'] );
		if ( empty( $emotion['name'] ) ) {
			return [];
		}

		if ( ! empty( $emotion['id'] ) ) {
			$emotion['url'] = wp_get_attachment_thumb_url( $emotion['id'] );

			return $emotion;
		}

		return empty( $emotion['url'] ) && ! empty( $this->default_emoji[ $emotion['name'] ]['url'] ) ?
			$this->default_emoji [ $emotion['name'] ] :
			$emotion;
	}

}
