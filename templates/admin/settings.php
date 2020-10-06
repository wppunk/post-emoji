<?php
/**
 * Settings HTML
 *
 * @package \Emoji\Templates
 *
 * @var array $settings List of settings.
 * @var int   $post_id  Post for example.
 */

// Exit if accessed directly.
use Emoji\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form action="options.php" method="POST">
		<?php settings_fields( Plugin::SLUG ); ?>
		<p>
			<label>
				<input
					type="checkbox"
					name="<?php echo esc_attr( Plugin::SLUG ); ?>[after_content]"
					value="1"
					<?php checked( true, ! empty( $settings['after_content'] ) ); ?>
				>
				<?php esc_html_e( 'Add emoji after content', 'post-emoji' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input
					type="checkbox"
					name="<?php echo esc_attr( Plugin::SLUG ); ?>[disable_styles]"
					value="1"
					<?php checked( true, ! empty( $settings['disable_styles'] ) ); ?>
				>
				<?php esc_html_e( 'Disable styles', 'post-emoji' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input
					type="checkbox"
					name="<?php echo esc_attr( Plugin::SLUG ); ?>[disable_scripts]"
					value="1"
					<?php checked( true, ! empty( $settings['disable_scripts'] ) ); ?>
				>
				<?php esc_html_e( 'Disable scripts', 'post-emoji' ); ?>
			</label>
		</p>
		<?php submit_button( esc_html__( 'Save', 'post-emoji' ) ); ?>
	</form>
	<?php if ( $post_id ) { ?>
		<h2>Preview</h2>
		<?php echo do_shortcode( '[emoji post_id="' . absint( $post_id ) . '"]' ); ?>
	<?php } ?>
</div>
