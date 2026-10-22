( function () {
	'use strict';

	function init() {
		document.querySelectorAll( '.shadcn-child-auth-tabs' ).forEach( function ( root, index ) {
			const remember = root.querySelector( '.woocommerce-form-login__rememberme' );
			const recovery = root.querySelector( '.lost_password a' );
			if ( remember && recovery && window.shadcnChildAccount ) {
				const label = remember.querySelector( 'span' );
				const recoveryParagraph = recovery.parentElement;
				const row = document.createElement( 'span' );
				row.className = 'shadcn-child-auth-options mb-4 flex w-full flex-wrap items-center justify-between gap-x-4 gap-y-2 text-sm';
				remember.before( row );
				row.append( remember, recovery );
				if ( label ) {
					label.textContent = '\u00a0' + window.shadcnChildAccount.keepSignedIn;
				}
				recovery.textContent = window.shadcnChildAccount.forgotPassword;
				if ( recoveryParagraph && ! recoveryParagraph.textContent.trim() && ! recoveryParagraph.children.length ) {
					recoveryParagraph.remove();
				}
			}
			const list = root.querySelector( '[data-auth-tablist]' );
			const tabs = Array.from( root.querySelectorAll( '[data-auth-tab]' ) );
			const forms = [ root.querySelector( 'form.login' ), root.querySelector( 'form.register' ) ];
			const panels = forms.map( function ( form ) {
				return form && form.closest( '.u-column1, .u-column2' );
			} );

			if ( ! list || tabs.length !== 2 || panels.some( function ( panel ) { return ! panel; } ) ) {
				return;
			}

			function select( selected, focus ) {
				tabs.forEach( function ( tab, position ) {
					const active = position === selected;
					tab.setAttribute( 'aria-selected', String( active ) );
					tab.tabIndex = active ? 0 : -1;
					panels[ position ].hidden = ! active;
				} );
				if ( focus ) {
					tabs[ selected ].focus();
				}
			}

			list.setAttribute( 'role', 'tablist' );
			tabs.forEach( function ( tab, position ) {
				const id = 'shadcn-auth-' + index + '-' + position;
				tab.id = id + '-tab';
				tab.setAttribute( 'role', 'tab' );
				tab.setAttribute( 'aria-controls', id + '-panel' );
				panels[ position ].id = id + '-panel';
				panels[ position ].setAttribute( 'role', 'tabpanel' );
				panels[ position ].setAttribute( 'aria-labelledby', tab.id );
				panels[ position ].tabIndex = 0;
				tab.addEventListener( 'click', function () { select( position, false ); } );
				tab.addEventListener( 'keydown', function ( event ) {
					let next;
					const rtl = getComputedStyle( root ).direction === 'rtl';
					if ( event.key === 'Home' ) {
						next = 0;
					} else if ( event.key === 'End' ) {
						next = tabs.length - 1;
					} else if ( event.key === 'ArrowRight' || event.key === 'ArrowLeft' ) {
						const direction = ( event.key === 'ArrowRight' ? 1 : -1 ) * ( rtl ? -1 : 1 );
						next = ( position + direction + tabs.length ) % tabs.length;
					} else {
						return;
					}
					event.preventDefault();
					select( next, true );
				} );
				forms[ position ].addEventListener( 'invalid', function () {
					select( position, false );
				}, true );
			} );
			select( root.dataset.initialTab === 'register' ? 1 : 0, false );
			const registerPrompt = root.querySelector( '[data-auth-register-prompt]' );
			if ( registerPrompt ) {
				registerPrompt.hidden = false;
				registerPrompt.querySelector( '[data-auth-register-link]' ).addEventListener( 'click', function () {
					select( 1, true );
				} );
			}
			root.dataset.enhanced = 'true';
			list.hidden = false;
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
