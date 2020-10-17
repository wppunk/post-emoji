<?php
/**
 * Settings HTML
 *
 * @package \Emoji\Templates
 *
 * @var array $emoji    List of emoji.
 * @var array $settings List of settings.
 * @var int   $post_id  Post for example.
 */

// Exit if accessed directly.
use Emoji\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$emoji_position = 0;
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form action="options.php" method="POST">
		<?php settings_fields( Plugin::SLUG ); ?>
		<div class="emoji-repeater-wrapper">
			<div class="emoji-repeater">
				<?php foreach ( $emoji as $emotion => $image ) { ?>
					<div class="emoji-repeater-row">
						<div class="emoji-repeater-item">
							<button class="button button-primary emoji-repeater-item-remove">
								<i class="dashicons dashicons-no-alt"></i>
							</button>
						</div>
						<div class="emoji-repeater-item">
							<div class="emoji-repeater-item-preview" style="background-image: url(<?php echo esc_url( $image['url'] ); ?>)"></div>
							<label>
								<input
									type="hidden"
									name="<?php echo esc_attr( Plugin::SLUG ); ?>[emoji][<?php echo absint( $emoji_position ); ?>][id]"
									value="<?php echo absint( $image['id'] ); ?>"
								/>
							</label>
						</div>
						<div class="emoji-repeater-item">
							<label>
								<input
									type="text"
									name="<?php echo esc_attr( Plugin::SLUG ); ?>[emoji][<?php echo absint( $emoji_position ); ?>][name]"
									value="<?php echo esc_attr( $emotion ); ?>"
								/>
							</label>
						</div>
					</div>
					<?php
					$emoji_position ++;
				}
				?>
			</div>
			<button class="button button-primary emoji-repeater-item-add">
				<i class="dashicons dashicons-plus-alt2"></i><?php esc_html_e( 'Add a new emotion', 'post-emoji' ); ?>
			</button>
		</div>
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
