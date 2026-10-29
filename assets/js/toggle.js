( function () {
	'use strict';

	const sunIcon = '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path></svg>';
	const moonIcon = '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path></svg>';

	function updateButtons( isDark ) {
		document.querySelectorAll( '.dark-mode-toggle' ).forEach( function ( toggle ) {
			let button = toggle.closest( 'button' );

			// Core's Icon block renders an SVG, while the parent script expects a
			// clickable .dark-mode-toggle control. Promote it to a real button.
			if ( ! button ) {
				button = document.createElement( 'button' );
				button.className = toggle.getAttribute( 'class' ) || '';
				toggle.replaceWith( button );
			}

			button.setAttribute( 'type', 'button' );
			button.setAttribute( 'aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode' );
			button.setAttribute( 'aria-pressed', isDark ? 'true' : 'false' );
			button.classList.add( 'shadcn-child-toggle' );
			button.classList.add(
				'inline-flex', 'h-9', 'w-9', 'items-center', 'justify-center',
				'border-0', 'rounded', 'bg-transparent', 'text-foreground', 'cursor-pointer',
				'transition-[background-color,color]', 'duration-150', 'ease-[ease]',
				'hover:bg-accent', 'hover:text-accent-foreground',
				'focus-visible:bg-accent', 'focus-visible:text-accent-foreground',
				'[&_svg]:h-[1.125rem]', '[&_svg]:w-[1.125rem]'
			);
			button.innerHTML = isDark ? sunIcon : moonIcon;
		} );
	}

	function init() {
		updateButtons( document.documentElement.classList.contains( 'dark' ) );
		document.addEventListener( 'darkModeChanged', function ( event ) {
			updateButtons( Boolean( event.detail && event.detail.isDark ) );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
