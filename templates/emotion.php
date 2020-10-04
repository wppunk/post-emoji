<?php
/**
 * Emotion HTML
 *
 * @package \Emoji\Templates
 *
 * @var string $emotion      Current emotion.
 * @var string $user_emotion User emotion.
 * @var int    $score        Emotion score.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$emotion_url = apply_filters( 'emoji_emotion_url', plugin_dir_url( __DIR__ ) . 'assets/build/img/' . $emotion . '.svg', $emotion );
?>

<a
	href="#"
	class="emoji-emotion <?php echo $user_emotion === $emotion ? 'active' : ''; ?>"
	data-type="<?php echo esc_attr( $emotion ); ?>">
	<span
		class="emoji-emotion-icon emoji-emotion-icon--<?php echo esc_attr( $emotion ); ?>"
		style="background-image: url( <?php echo esc_url( $emotion_url ); ?> );">
	</span>
	<span class="emoji-emotion-label emoji-emotion-label--<?php echo esc_attr( $emotion ); ?>"><?php echo absint( $score ); ?></span>
</a>
