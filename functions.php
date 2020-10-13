<?php
/**
 * Emoji functions
 *
 * @package Emoji/Functions
 */

use Emoji\DB;
use Emoji\Emoji;
use Emoji\UserUuid;


/**
 * Get post emoji
 *
 * @param int $post_id Post ID.
 *
 * @return string
 */
function get_emoji( $post_id = 0 ) {
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;

	return do_shortcode(
		sprintf(
			'[emoji post_id=%d]',
			absint( $post_id )
		)
	);
}

/**
 * Print post emoji
 *
 * @param int $post_id Post ID.
 */
function the_emoji( $post_id = 0 ) {
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;

	echo wp_kses_post( get_emoji( $post_id ) );
}

/**
 * Get post emoji count
 *
 * @param int $post_id Post ID.
 *
 * @return int
 */
function get_emoji_count( $post_id = 0 ) {
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;

	return array_sum(
		( new Emoji( new DB(), new UserUuid() ) )->get( absint( $post_id ) )
	);
}

/**
 * Print emoji count
 *
 * @param int $post_id Post ID.
 */
function the_emoji_count( $post_id = 0 ) {
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;

	echo absint( get_emoji_count( $post_id ) );
}
