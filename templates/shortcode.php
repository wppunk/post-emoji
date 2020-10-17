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
	foreach ( $emoji as $name => $emotion ) {
		do_action( "emoji_before_{$name}_emotion", $emoji, $emotion );
		require plugin_dir_path( __FILE__ ) . 'emotion.php';
		do_action( "emoji_after_{$name}_emotion", $emoji, $emotion );
	}
	do_action( 'emoji_after_emoji', $emoji, $user_emotion );
	?>
</div>
<?php do_action( 'emoji_after_container', $emoji, $user_emotion ); ?>
