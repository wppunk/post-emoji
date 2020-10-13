<?php
/**
 * User
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package Emoji
 * @author  WPPunk
 */

namespace Emoji;

/**
 * Class Lock
 *
 * @package Emoji
 */
class Lock {

	/**
	 * User uuid
	 *
	 * @var \Emoji\UserUuid
	 */
	private $user_uuid;

	/**
	 * Lock constructor.
	 *
	 * @param \Emoji\UserUuid $user_uuid User uuid.
	 */
	public function __construct( $user_uuid ) {
		$this->user_uuid = $user_uuid;
	}

	/**
	 * Is the request locked to this post for current user?
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return bool
	 */
	public function is_locked( $post_id ) {
		return ! empty( get_transient( 'emoji_lock_' . $this->user_uuid->create( $post_id ) ) );
	}

	/**
	 * Lock request to this post for current user.
	 *
	 * @param int $post_id Post ID.
	 */
	public function lock( $post_id ) {
		set_transient( 'emoji_lock_' . $this->user_uuid->create( $post_id ), 1, 3600 );
	}

	/**
	 * Unlock request to this post for current user.
	 *
	 * @param int $post_id Post ID.
	 */
	public function unlock( $post_id ) {
		delete_transient( 'emoji_lock_' . $this->user_uuid->create( $post_id ) );
	}

}
