import { Vote } from './vote';

(function() {

	const emojiContainers = document.querySelectorAll( '.emoji-container' );

	if ( ! emojiContainers.length ) {
		return;
	}

	new Vote( '' );
	emojiContainers.forEach( ( el ) => {
		el.querySelectorAll( '.emoji-emotion' ).forEach( ( el ) => {
			el.addEventListener( 'click', ( e) => {
				e.preventDefault();
				new Vote( el.getAttribute( 'data-type' ) );
			});
		} );
	} );
}());
