/* global emoji */

/**
 * Class Vote.
 *
 * @since 1.0.0
 */
export class Vote {

	/**
	 * Settings constructor.
	 *
	 * @since 1.0.0
	 */
	constructor( emotion ) {
		const emojiContainer = document.getElementsByClassName( 'emoji-container' )[ 0 ],
			active = emojiContainer.getElementsByClassName( 'active' );

		if ( emojiContainer.classList.contains( 'disabled' ) ) {
			return;
		}

		if ( 'poop' === emotion ) {
			this.farting( emojiContainer );
			return;
		}

		if ( active.length ) {
			active[ 0 ].classList.remove( 'active' );
		}
		emojiContainer.classList.add( 'disabled' );

		this.sendAjax( emojiContainer, emotion );
	}

	/**
	 * Send AJAX
	 *
	 * @param emojiContainer
	 * @param emotion
	 */
	sendAjax( emojiContainer, emotion ) {
		if ( 'undefined' === emoji ) {
			return;
		}
		const vote = this,
			xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if ( 4 !== this.readyState || 200 !== this.status ) {
				return;
			}
			vote.ajaxSuccess( emojiContainer, JSON.parse( xhttp.responseText ) );
		};
		xhttp.open( 'POST', emoji.url, true );
		xhttp.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		xhttp.send( 'action=emotion&nonce=' + emoji.nonce + '&post_id=' + emoji.post_id + '&emotion=' + emotion );
	}

	/**
	 * Ajax success handler
	 *
	 * @param emojiContainer
	 * @param response
	 */
	ajaxSuccess( emojiContainer, response ) {
		if ( response.data.active ) {
			emojiContainer.querySelectorAll( '.emoji-emotion[data-type=' + response.data.active + ']' )[ 0 ].classList.add( 'active' );
		}
		if ( response.data.emoji ) {
			for ( let [ emotion, score ] of Object.entries( response.data.emoji ) ) {
				emojiContainer.querySelectorAll( '.emoji-emotion[data-type=' + emotion + '] .emoji-emotion-label' )[ 0 ].textContent = score.toString();
			}
		}
		emojiContainer.classList.remove( 'disabled' );
	}

	/**
	 * Farting
	 *
	 * @param emojiContainer
	 */
	farting( emojiContainer ) {
		const poop = emojiContainer.querySelectorAll( '.emoji-emotion[data-type=poop]' )[ 0 ],
			poopCounter = emojiContainer.querySelectorAll( '.emoji-emotion[data-type=poop] .emoji-emotion-label' )[ 0 ],
			audio = Math.floor( Math.random() * 10 ) + 1;
		poop.classList.add( 'active' );
		poopCounter.textContent = 1;

		new Audio( '/wp-content/plugins/emoji/assets/audio/farting-' + audio + '.mp3' ).play();
		setTimeout( function() {
			poop.classList.remove( 'active' );
			poopCounter.textContent = 0;
			emojiContainer.classList.remove( 'disabled' );
		}, 500 );
	}
}
