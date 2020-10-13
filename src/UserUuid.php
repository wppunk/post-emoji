<?php
/**
 * UserUuid
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package Emoji
 * @author  WPPunk
 */

namespace Emoji;

/**
 * Class UserUuid
 *
 * @package Emoji
 */
class UserUuid {

	/**
	 * Create hash for current user
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string
	 */
	public function create( $post_id ) {
		$user_ip = $this->get_user_ip();

		return apply_filters(
			'emoji_user_hash',
			hash(
				'sha256',
				sprintf(
					'%d-%s',
					absint( $post_id ),
					$user_ip
				)
			),
			$post_id,
			$user_ip
		);
	}

	/**
	 * Get user IP.
	 *
	 * @return string
	 */
	private function get_user_ip() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$user_ip = (string) apply_filters( 'emoji_get_user_ip', '' );
		if ( $user_ip ) {
			return $user_ip;
		}
		foreach (
			[
				'HTTP_X_REAL_IP',
				'MMDB_ADDR',
				'HTTP_CLIENT_IP',
				'HTTP_X_FORWARDED_FOR',
				'REMOTE_ADDR',
			] as $key
		) {
			if ( ! empty( $_SERVER[ $key ] ) ) {
				return sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );
			}
		}

		return '127.0.0.1';
	}

}
