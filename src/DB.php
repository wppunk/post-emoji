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
			`post_id` INT NOT NULL,
			`emotion` VARCHAR(30) NOT NULL,
			`hash` VARCHAR(64) UNIQUE,
			`date_time` DATETIME DEFAULT CURRENT_TIMESTAMP
		) ' . $wpdb->get_charset_collate();

		maybe_create_table( self::get_emoji_table(), $sql );
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
		$emoji = (array) $wpdb->get_results(
			$wpdb->prepare(
				'SELECT `emotion`, COUNT( `emotion` ) as count FROM ' . esc_sql( self::get_emoji_table() ) . ' WHERE `post_id` = %d GROUP BY `emotion`',
				absint( $post_id )
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
		if ( empty( $emoji ) ) {
			return [];
		}

		$emoji = wp_list_pluck( $emoji, 'count', 'emotion' );
		$emoji = array_map( 'absint', $emoji );

		return $emoji;
	}

	/**
	 * Get count of emoji for current post.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $emoji   List of emoji.
	 *
	 * @return int
	 */
	public function get_emoji_count( $post_id, $emoji ) {
		global $wpdb;
		$placeholder = array_fill( 0, count( $emoji ), '%s' );
		foreach ( $emoji as &$emotion ) {
			$emotion = sanitize_text_field( $emotion );
		}
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		$count = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT COUNT( `emotion` ) as count FROM ' . esc_sql( self::get_emoji_table() ) .
				' WHERE `post_id` = %d
				AND `emotion` IN (' . implode( ',', $placeholder ) . ')', // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				array_merge(
					[
						absint( $post_id ),
					],
					$emoji
				)
			)
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching

		return absint( $count );
	}

	/**
	 * Get user emotion.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $hash    Hash.
	 *
	 * @return string
	 */
	public function get_user_emotion( $post_id, $hash ) {
		global $wpdb;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		return (string) $wpdb->get_var(
			$wpdb->prepare(
				'SELECT `emotion` FROM ' . esc_sql( self::get_emoji_table() ) . ' WHERE `post_id` = %d AND `hash` = %s LIMIT 1',
				$post_id,
				$hash
			)
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
	}

	/**
	 * Update user emotion
	 *
	 * @param int    $post_id      Current post ID.
	 * @param string $hash         Hash.
	 * @param string $user_emotion Current user emotion.
	 *
	 * @return bool
	 */
	public function update_user_emotion( $post_id, $hash, $user_emotion ) {
		global $wpdb;
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		$deleted = $wpdb->delete(
			self::get_emoji_table(),
			[
				'post_id' => $post_id,
				'emotion' => $user_emotion,
				'hash'    => $hash,
			]
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
		if ( ! empty( $deleted ) ) {
			return false;
		}

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		return (bool) $wpdb->replace(
			self::get_emoji_table(),
			[
				'post_id' => $post_id,
				'emotion' => $user_emotion,
				'hash'    => $hash,
			],
			[
				'%d',
				'%s',
				'%s',
			]
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
	}

}
