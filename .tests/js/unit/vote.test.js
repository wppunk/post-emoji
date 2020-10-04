import { Vote } from '../../../assets/.src/js/vote';

describe( 'Main constructor', () => {
	it( 'Vote ran', () => {
		document.body.innerHTML = '<div class="emoji-container"></div>';
		new Vote( 'happy' );
	} );
} );
