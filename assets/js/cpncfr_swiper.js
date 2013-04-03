/*
================================================================================
Javascript for Swipe functionality in the NCFR Theme
================================================================================
AUTHOR: Christian Wach <needle@haystack.co.uk>
--------------------------------------------------------------------------------
NOTES

Implements touch swipe events

--------------------------------------------------------------------------------
*/




/** 
 * @description: define what happens when the page is ready
 *
 */
jQuery(document).ready( function($) {
	
	// init
	var active_column = 'main';
	
	// enable swiping...
	$( '#main_wrapper' ).swipe( {

		// swipe handler for left swipes
		swipeLeft: function(event, direction, distance, duration, fingerCount) {
			
			//$( '#tagline' ).html( 'You swiped left' );
			
			// if none of the buttons have an active class
			if (
				! $( '.navigation-button' ).hasClass( 'active-button' ) &&
				! $( '.content-button' ).hasClass( 'active-button' ) &&
				! $( '.sidebar-button' ).hasClass( 'active-button' ) 
			) {
				showSidebar();
				return;
			}
		
			// is nav active?
			if ( $( '.navigation-button' ).hasClass( 'active-button' ) ) {
				showContent();
				return;
			}
		
			// is content active?
			if ( $( '.content-button' ).hasClass( 'active-button' ) ) {
				showSidebar();
				return;
			}
			
			// ignoring anything else
		
		},
		
		// swipe handler for right swipes
		swipeRight: function(event, direction, distance, duration, fingerCount) {
			
			//$( '#tagline' ).html( 'You swiped right' );
			
			// if none of the buttons have an active class
			if (
				! $( '.navigation-button' ).hasClass( 'active-button' ) &&
				! $( '.content-button' ).hasClass( 'active-button' ) &&
				! $( '.sidebar-button' ).hasClass( 'active-button' ) 
			) {
				showMenu();
				return;
			}
		
			// is content active?
			if ( $( '.content-button' ).hasClass( 'active-button' ) ) {
				showMenu();
				return;
			}
			
			// is sidebar active?
			if ( $( '.sidebar-button' ).hasClass( 'active-button' ) ) {
				showContent();
				return;
			}
		
			// ignoring anything else
		
		},
		
		// default is 75px, set to 0 for demo so any distance triggers swipe
		threshold: 150,
		
		// exclude child elements that we want clicks for
		excludedElements: 'a.comment_block_permalink, a.comment_permalink, h3.activity_heading, '+
						  'a.comment_on_post, a.para_permalink, .post_title a, .textblock, '+
						  'span.para_marker a, a.cp_para_link, span.footnotereverse a, a.footnote-back-link, '+
						  '.simple-footnotes ol li > a, a.simple-footnote, sup.footnote a, '+
						  'sup a.footnote-identifier-link, a.zp-ZotpressInText, .gallery'
		
	});
    
});
