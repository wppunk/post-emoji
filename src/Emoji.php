<?php
/**
 * Emoji
 *
 * @since   1.0.0
 * @link    {URL}
 * @license GPLv2 or later
 * @package Emoji
 * @author  {AUTHOR}
 */

namespace Emoji;

/**
 * Class Emoji
 *
 * @package Emoji
 */
class Emoji {

	/**
	 * DB
	 *
	 * @var \Emoji\DB
	 */
	private $db;

	/**
	 * Emoji constructor.
	 *
	 * @param \Emoji\DB $db DB.
	 */
	public function __construct( $db ) {
		$this->db = $db;
	}

	/**
	 *  Create hash for current user
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string
	 */
	private function hash( $post_id ) {
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
	private function get_user_ip() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$user_ip = (string) apply_filters( 'emoji_get_user_ip', '' );
		if ( $user_ip ) {
			return $user_ip;
		}

		if ( ! empty( $_SERVER['HTTP_X_REAL_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
		}
		if ( ! empty( $_SERVER['MMDB_ADDR'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
		}
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		}
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		}
		if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}

		return '127.0.0.1';
	}

	/**
	 * Get post emoji.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array
	 */
	public function get( $post_id ) {
		$emoji = wp_cache_get( 'emoji_' . $post_id );
		if ( $emoji ) {
			return $emoji;
		}

		$emoji = array_merge(
			[
				'cool'  => 0,
				'happy' => 0,
				'good'  => 0,
				'nerd'  => 0,
				'sad'   => 0,
				'poop'  => 0,
			],
			$this->db->get_emoji( $post_id )
		);
		wp_cache_set( 'emoji_' . $post_id, $emoji );

		return $emoji;
	}

	/**
	 * Get user emotion.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string
	 */
	public function user_emotion( $post_id ) {
		$hash    = $this->hash( $post_id );
		$emotion = wp_cache_get( 'emotion_' . $hash );
		if ( $emotion ) {
			return $emotion;
		}
		$emotion = $this->db->get_user_emotion( $hash );
		wp_cache_set( 'emotion_' . $hash, $emotion );

		return $emotion;
	}

	/**
	 * User vote for post
	 *
	 * @param int    $post_id               Post ID.
	 * @param string $user_emotion          User emotion.
	 * @param string $previous_user_emotion Previous user emotion.
	 */
	public function vote( $post_id, $user_emotion, $previous_user_emotion ) {
		do_action( 'emoji_before_vote' );
		$emoji = $this->get( $post_id );
		unset( $emoji['poop'] ); // Remove poop emoji because column poop doesn't exist.
		if ( ! isset( $emoji[ $user_emotion ] ) ) {
			return;
		}
		if ( $previous_user_emotion && ! isset( $emoji[ $previous_user_emotion ] ) ) {
			return;
		}
		$this->db->update_emoji( $post_id, $user_emotion, $previous_user_emotion );
		wp_cache_delete( 'emoji_' . $post_id );
		$user_hash = $this->hash( $post_id );
		$this->db->update_user_emotion( $user_hash, $user_emotion, $previous_user_emotion );
		wp_cache_delete( 'emotion_' . $user_hash );
		do_action( 'emoji_voted' );
	}

}
