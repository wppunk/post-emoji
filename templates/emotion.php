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

?>

<a
	href="#"
	class="emoji-emotion <?php echo $user_emotion === $name ? 'active' : ''; ?>"
	data-type="<?php echo esc_attr( $name ); ?>">
	<span
		class="emoji-emotion-icon emoji-emotion-icon--<?php echo esc_attr( $name ); ?>"
		style="background-image: url( <?php echo esc_url( $emotion['url'] ); ?> );">
	</span>
	<span class="emoji-emotion-label emoji-emotion-label--<?php echo esc_attr( $name ); ?>"><?php echo absint( $emotion['score'] ); ?></span>
</a>
