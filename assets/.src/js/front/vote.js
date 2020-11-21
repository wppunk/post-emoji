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
		const emojiContainers = document.querySelectorAll( '.emoji-container' ),
			active = document.querySelectorAll( '.emoji-container .active' );

		if ( emojiContainers[ 0 ].classList.contains( 'disabled' ) ) {
			return;
		}

		if ( 'poop' === emotion ) {
			this.farting( emojiContainers[ 0 ]);
			return;
		}

		active.forEach( ( el ) => {
			el.classList.remove( 'active' );
		});

		emojiContainers.forEach( ( el ) => {
			el.classList.add( 'disabled' );
		});

		this.sendAjax( emojiContainers, emotion );
	}

	/**
	 * Send AJAX
	 *
	 * @param emojiContainers
	 * @param emotion
	 */
	sendAjax( emojiContainers, emotion ) {
		const vote = this,
			xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if ( 4 !== this.readyState || 200 !== this.status ) {
				return;
			}
			vote.ajaxSuccess( emojiContainers, JSON.parse( xhttp.responseText ) );
		};
		xhttp.open( 'POST', emoji.url, true );
		xhttp.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		xhttp.send( 'action=emotion&nonce=' + emoji.nonce + '&post_id=' + emoji.post_id + '&emotion=' + emotion );
	}

	/**
	 * Ajax success handler
	 *
	 * @param emojiContainers
	 * @param response
	 */
	ajaxSuccess( emojiContainers, response ) {
		if ( response.data.active ) {
			emojiContainers.forEach( ( el ) => {
				el.querySelector( '.emoji-emotion[data-type=' + response.data.active + ']' ).classList.add( 'active' );
			});
		}
		if ( response.data.emoji ) {
			for ( let [ emotion, data ] of Object.entries( response.data.emoji ) ) {
				emojiContainers.forEach( ( el ) => {
					el.querySelectorAll( '.emoji-emotion[data-type=' + emotion + '] .emoji-emotion-label' )[ 0 ].textContent = data.score.toString();
				});
			}
		}
		emojiContainers.forEach( ( el ) => {
			el.classList.remove( 'disabled' );
		});
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

		new Audio( emoji.audio_dir + 'farting-' + audio + '.mp3' ).play();
		setTimeout( function() {
			poop.classList.remove( 'active' );
			poopCounter.textContent = 0;
			emojiContainer.classList.remove( 'disabled' );
		}, 500 );
	}
}
