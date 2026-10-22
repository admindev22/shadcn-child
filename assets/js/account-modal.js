( function () {
	'use strict';

	const dialog = document.getElementById( 'shadcn-child-account-modal' );
	if ( ! dialog || typeof dialog.showModal !== 'function' ) {
		return;
	}
	const content = dialog.querySelector( '[data-account-modal-content]' );
	let opener;
	let placeholder;
	let movedForm;
	let previousOverflow;

	document.addEventListener( 'click', function ( event ) {
		const link = event.target.closest( 'a.wc-block-customer-account__link' );
		if ( ! link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target === '_blank' ) {
			return;
		}
		if ( ! content.querySelector( '.shadcn-child-auth-tabs' ) ) {
			movedForm = document.querySelector( '.shadcn-child-auth-tabs' );
			if ( ! movedForm ) {
				return;
			}
			placeholder = document.createComment( 'Account form position' );
			movedForm.before( placeholder );
			content.append( movedForm );
		}
		event.preventDefault();
		opener = link;
		// Submit to WooCommerce's account page so validation/notices use its normal flow.
		content.querySelectorAll( 'form.login, form.register' ).forEach( function ( form ) {
			form.action = dialog.dataset.accountUrl;
		} );
		previousOverflow = document.documentElement.style.overflow;
		dialog.showModal();
		document.documentElement.style.overflow = 'hidden';
		const selected = dialog.querySelector( '[role="tab"][aria-selected="true"], input:not([type="hidden"])' );
		if ( selected ) {
			selected.focus();
		}
	} );

	dialog.querySelector( '[data-account-modal-close]' ).addEventListener( 'click', function () {
		dialog.close();
	} );
	dialog.addEventListener( 'click', function ( event ) {
		if ( event.target !== dialog ) {
			return;
		}
		const bounds = dialog.getBoundingClientRect();
		if ( event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom ) {
			dialog.close();
		}
	} );
	dialog.addEventListener( 'close', function () {
		document.documentElement.style.overflow = previousOverflow;
		if ( placeholder && movedForm ) {
			placeholder.replaceWith( movedForm );
			placeholder = null;
			movedForm = null;
		}
		if ( opener && opener.isConnected ) {
			opener.focus();
		}
	} );
}() );
