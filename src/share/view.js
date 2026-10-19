/**
 * Share button: the native share sheet where the Web Share API exists,
 * otherwise the dialog with copy buttons. Also an open/close fallback for
 * browsers without invoker commands (commandfor/command).
 */
document.querySelectorAll( '.wp-block-autonomie-share__modal' ).forEach( ( dialog ) => {
	const button = dialog.parentElement.querySelector( '.wp-block-autonomie-share__button' );

	if ( navigator.share ) {
		button.addEventListener( 'click', ( event ) => {
			// Cancels the invoker command, so the dialog stays closed.
			event.preventDefault();
			navigator.share( { title: dialog.dataset.shareTitle, url: dialog.dataset.shareUrl } ).catch( () => {} );
		} );
	} else if ( ! ( 'commandForElement' in HTMLButtonElement.prototype ) ) {
		button.addEventListener( 'click', () => dialog.showModal() );
		dialog.querySelector( '.wp-block-autonomie-share__close' ).addEventListener( 'click', () => dialog.close() );
	}

	if ( navigator.clipboard ) {
		dialog.querySelectorAll( '[data-share-copy]' ).forEach( ( copy ) => {
			const label = copy.textContent;

			copy.hidden = false;
			copy.addEventListener( 'click', () => {
				const field = document.getElementById( copy.dataset.shareCopy );

				navigator.clipboard.writeText( field.value ).then( () => {
					copy.textContent = copy.dataset.shareCopied;
					setTimeout( () => ( copy.textContent = label ), 2000 );
				} );
			} );
		} );
	}

	// Select the whole value on focus, so it is ready to copy by hand.
	dialog.querySelectorAll( 'input, textarea' ).forEach( ( field ) => {
		field.addEventListener( 'focus', () => field.select() );
	} );

	// Close on a click on the backdrop, for browsers without closedby="any".
	dialog.addEventListener( 'click', ( event ) => {
		if ( event.target === dialog ) {
			dialog.close();
		}
	} );
} );
