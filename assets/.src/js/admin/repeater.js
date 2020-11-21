/**
 * Class Repeater.
 *
 * @since 1.0.0
 */
export class Repeater {

	/**
	 * Settings constructor.
	 *
	 * @since 1.0.0
	 */
	constructor() {
		this.$repeaterElement = jQuery( '.emoji-repeater' );
		this.$addItemButton = jQuery( '.emoji-repeater-item-add' );
		this.removeSelector = '.emoji-repeater-item-remove';
		this.itemSelector = '.emoji-repeater-row';

		this.bindRemoveItem = this.bindRemoveItem.bind( this );
		this.bindCloneItem = this.bindCloneItem.bind( this );
		this.bindChangeIndexes = this.bindChangeIndexes.bind( this );
	}

	init() {
		this.initSortable();

		this.$addItemButton.on( 'click', this.bindCloneItem );
		this.$repeaterElement.on( 'click', this.removeSelector, this.bindRemoveItem );
	}

	initSortable() {
		this.$repeaterElement.sortable({
			axis: 'y',
			stop: this.bindChangeIndexes
		});
	}

	bindChangeIndexes() {
		this.$repeaterElement.find( this.itemSelector ).each( Repeater.changeIndexes );
	}

	static changeIndexes() {
		return Repeater.changeItemIndexes( jQuery( this ) );
	}

	static changeItemIndexes( $this ) {
		const index = +$this.index();
		$this.find( 'input' ).each( function() {
			jQuery( this ).prop( 'name', Repeater.changeIndex( jQuery( this ).prop( 'name' ), index ) );
		});

		return $this;
	}

	static changeIndex( str, index ) {
		return str.toString().replace( new RegExp( /\[\d+]/gm ), '[' + index + ']' );
	}

	reinitSortable() {
		this.$repeaterElement.sortable( 'destroy' );
		this.initSortable();
	}

	bindRemoveItem( e ) {
		e.preventDefault();

		this.removeItem( jQuery( e.target ) );
	}

	removeItem( $element ) {
		$element.closest( '.emoji-repeater-row' ).slideUp(
			400,
			function() {
				$element.remove();
			});
	}

	bindCloneItem( e ) {
		e.preventDefault();
		this.cloneItem();
	}

	cloneItem() {
		const last = this.$repeaterElement.find( '.emoji-repeater-row:last' ),
			clone = this.clearItem( last.clone() );

		clone.find( 'input' ).each( function() {
			jQuery( this ).prop(
				'name',
				Repeater.changeIndex( jQuery( this ).prop( 'name' ), +last.index() + 1 )
			);
		});

		jQuery( '.emoji-repeater' ).append( clone );
		clone.slideDown();
		this.reinitSortable();
	}

	clearItem( clone ) {
		clone.removeProp( 'style' );
		clone.find( 'input' ).val( '' );
		clone.find( '.emoji-repeater-item-preview' ).removeProp( 'style' );
		clone.css( 'display', 'none' );

		return clone;
	}
}
