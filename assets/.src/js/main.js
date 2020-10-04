import {Vote} from './vote';

( function() {
	document.querySelectorAll( '.emoji-container a' ).forEach( a =>
		a.addEventListener( 'click', ( e ) => {
			e.preventDefault();
			new Vote( a.getAttribute( 'data-type' ) );
		}) );
}() );
