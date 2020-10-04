<?php
/**
 * View for the shortcode.
 *
 * @package \Emoji\Templates
 *
 * @var array  $emoji        List of emotion.
 * @var string $user_emotion User emotion.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php do_action( 'emoji_before_container', $emoji, $user_emotion ); ?>
<div class="emoji-container">
	<?php
	do_action( 'emoji_before_emoji', $emoji, $user_emotion );
	foreach ( $emoji as $emotion => $score ) {
		do_action( "emoji_before_{$emotion}_emotion", $emoji, $score );
		require plugin_dir_path( __FILE__ ) . 'emotion.php';
		do_action( "emoji_after_{$emotion}_emotion", $emoji, $score );
	}
	do_action( 'emoji_after_emoji', $emoji, $user_emotion );
	?>
</div>
<?php do_action( 'emoji_after_container', $emoji, $user_emotion ); ?>
