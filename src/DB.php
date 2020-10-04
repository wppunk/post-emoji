<?php
/**
 * Work with DB
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package Emoji
 * @author  WPPunk
 */

namespace Emoji;

/**
 * Class DB
 *
 * @package Emoji
 */
class DB {

	/**
	 * Get emoji table name
	 *
	 * @return string
	 */
	private static function get_emoji_table() {
		global $wpdb;

		return $wpdb->prefix . 'emoji';
	}

	/**
	 * Get a table of votes
	 *
	 * @return string
	 */
	private static function get_vote_table() {
		global $wpdb;

		return $wpdb->prefix . 'emoji_vote';
	}

	/**
	 * Create plugin custom tables.
	 */
	public static function create_tables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		global $wpdb;

		$sql = 'CREATE TABLE ' . self::get_emoji_table() . ' (
			`post_id` INT NOT NULL UNIQUE,
			`cool` INT UNSIGNED,
			`happy` INT UNSIGNED,
			`good` INT UNSIGNED,
			`nerd` INT UNSIGNED,
			`sad` INT UNSIGNED
		) ' . $wpdb->get_charset_collate();

		maybe_create_table( self::get_emoji_table(), $sql );

		$sql = 'CREATE TABLE ' . self::get_vote_table() . ' (
			`hash` VARCHAR(64) UNIQUE,
			`emotion` VARCHAR(5) NOT NULL
		) ' . $wpdb->get_charset_collate();

		maybe_create_table( self::get_vote_table(), $sql );
	}

	/**
	 * Get post's emoji.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array
	 */
	public function get_emoji( $post_id ) {
		global $wpdb;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		return (array) $wpdb->get_row(
			$wpdb->prepare(
				'SELECT cool, happy, good, nerd, sad FROM ' . esc_sql( self::get_emoji_table() ) . ' WHERE post_id = %d',
				absint( $post_id )
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
	}

	/**
	 * Get user emotion.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string
	 */
	public function get_user_emotion( $post_id ) {
		global $wpdb;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		return (string) $wpdb->get_var(
			$wpdb->prepare(
				'SELECT emotion FROM ' . esc_sql( self::get_vote_table() ) . ' WHERE hash = %s LIMIT 1',
				$post_id
			)
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
	}

	/**
	 * Update emoji.
	 *
	 * @param int    $post_id               Post ID.
	 * @param string $user_emotion          User emotion.
	 * @param string $previous_user_emotion Previous user emotion.
	 */
	public function update_emoji( $post_id, $user_emotion, $previous_user_emotion ) {
		global $wpdb;

		$multiple = $previous_user_emotion !== $user_emotion ? 1 : - 1;

		$sql = $wpdb->prepare(
			'INSERT INTO ' . esc_sql( self::get_emoji_table() )
			. '(`post_id`, `cool`, `happy`, `good`, `nerd`, `sad`) VALUES( %d, %d, %d, %d, %d, %d )
			ON DUPLICATE KEY UPDATE post_id=%d,' . esc_sql( $user_emotion ) . '=' . esc_sql( $user_emotion ) . ' + %d',
			absint( $post_id ),
			absint( 'cool' === $user_emotion ),
			absint( 'happy' === $user_emotion ),
			absint( 'good' === $user_emotion ),
			absint( 'nerd' === $user_emotion ),
			absint( 'sad' === $user_emotion ),
			absint( $post_id ),
			$multiple
		);
		if ( $previous_user_emotion && 1 === $multiple ) {
			$sql .= ', ' . $previous_user_emotion . '=' . $previous_user_emotion . '-1';
		}
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$wpdb->query( $sql );
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Update user emotion
	 *
	 * @param string $hash                  Hash.
	 * @param string $user_emotion          Current user emotion.
	 * @param string $previous_user_emotion User emotion.
	 */
	public function update_user_emotion( $hash, $user_emotion, $previous_user_emotion ) {
		global $wpdb;
		if ( $previous_user_emotion === $user_emotion ) {
			// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
			// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->delete(
				self::get_vote_table(),
				[ 'hash' => $hash ]
			);
			// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
			// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
			return;
		}

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->replace(
			self::get_vote_table(),
			[
				'hash'    => $hash,
				'emotion' => $user_emotion,
			],
			[
				'%s',
				'%s',
			]
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
	}

}
