const media = wp.media( {
	title: 'Insert image',
	library: {
		type: 'image',
	},
	button: {
		text: 'Use this image', // button label text
	},
	multiple: false,
} );
let currentItem;

/**
 * Class Upload.
 *
 * @since 1.0.0
 */
export class Upload {

	/**
	 * Upload constructor.
	 *
	 * @since 1.0.0
	 */
	constructor() {
		this.$wrapper = jQuery( '.emoji-repeater' );
	}

	init() {
		this.$wrapper.on( 'click', '.emoji-repeater-item-preview', this.openMediaLibrary );
		media.on( 'select', this.chooseImage );
	}

	openMediaLibrary() {
		currentItem = jQuery( this ).closest( '.emoji-repeater-item' );
		media.open();
	}

	chooseImage() {
		const attachment = media.state().get( 'selection' ).first().toJSON();
		currentItem.find( 'input' ).val( attachment.id );
		currentItem.find( '.emoji-repeater-item-preview' ).css( 'background-image', 'url(' + attachment.url + ')' );
	}
}
