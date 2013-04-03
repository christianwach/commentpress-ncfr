/*
================================================================================
Javascript for NCFR Theme
================================================================================
AUTHOR: Christian Wach <needle@haystack.co.uk>
--------------------------------------------------------------------------------
NOTES

Implements slider on browse-by-topic page

--------------------------------------------------------------------------------
*/




// define our vars
var cpncfr_page, styles;

// init vars
cpncfr_page = '';

// test for our localisation object
if ( 'undefined' !== typeof CpncfrSettings ) {
	
	// set our vars
	cpncfr_page = CpncfrSettings.cpncfr_page;

}



// init styles
styles = '';

// wrap with js test
if ( document.getElementById ) {

	// open style declaration
	styles += '<style type="text/css" media="screen">';

	// is it the browse by topic page?
	if ( cpncfr_page == 'browse_by_topic' ) {

		// hide browse by date
		styles += '#terms-date_filter > ul { display: none; } ';
	
	}
	
	// show special pages menu
	styles += '.page-template-directory-php #navigation .paragraph_wrapper.special_pages_wrapper { display: block; } ';
	styles += '.page-template-browse-by-topic-php #navigation .paragraph_wrapper.special_pages_wrapper { display: block; } ';

	// close style declaration
	styles += '</style>';
	
}

// write to page now
document.write( styles );




/** 
 * @description: define what happens when the page is ready
 *
 */
jQuery(document).ready( function($) {
	
	
	
	// is it the browse by topic page?
	if ( cpncfr_page == 'browse_by_topic' ) {

		// init slider values
		var slider_vals = [], date_text, slider_first, slider_last;
	
		// loop through our existing date terms
		$( '#terms-date_filter li.term-item label' ).each( function(i) {
		
			// get content, which is the date
			date_text = parseInt( $( this ).text().trim() );
			
			// trace
			//console.log( 'label: ' + date_text );
			
			// add text to array
			slider_vals.push( date_text );
		
		});
		
		// did we get one?
		if ( slider_vals.length > 0 ) {
		
			// get first and last
			slider_first = slider_vals[0];
			slider_last = slider_vals.pop();
	
			// create our slider element
			$( '#terms-date_filter' ).append( 
			
				'<div id="cpncfr-date-slider">' +
				'<p>' + 
				'<label for="amount">Date range:</label>' + 
				'<input type="text" id="cpncfr-range" />' + 
				'</p>' +
				'<div id="slider-range"></div>' +
				'</div>'
				
			);
			
			// init slider
			$( "#slider-range" ).slider({
			
				range: true,
				min: slider_first,
				max: slider_last,
				
				// initial range values
				values: [ slider_first, slider_last ],
				
				// while sliding...
				slide: function( event, ui ) {
				
					// update range text
					$( "#cpncfr-range" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
					
				}
		
			});
			
			// set range text
			$( "#cpncfr-range" ).val( 
			
				$( "#slider-range" ).slider( "values", 0 ) + " - " + 
				$( "#slider-range" ).slider( "values", 1 ) 
				
			);
			
		}
			
	
	
		/** 
		 * @description: taxonomy submission method
		 * @todo: 
		 *
		 */
		$('form.taxonomy-drilldown-checkboxes').on( 'submit', function( evt ) {
			
			var extremes = [], i, to_check;
		
			// add to array
			extremes.push( $( "#slider-range" ).slider( 'values', 0 ) );
			extremes.push( $( "#slider-range" ).slider( 'values', 1 ) );
			
			// init array of dates to check
			to_check = [];
			
			// start from first to last
			for( i = extremes[0]; i <= extremes[1]; i++ ) {
			
				// add to array
				//console.log( 'date: ' + i );
				to_check.push( i );
			
			}
		
			// loop through our existing date terms
			$( '#terms-date_filter li.term-item label' ).each( function(i) {
			
				// get content, which is the date
				date_text = parseInt( $( this ).text().trim() );
				
				// is it in the to_check array?
				if ( $.in_array( date_text, to_check ) ) {
				
					// check it
					$( 'input', this ).prop( 'checked', true );
				
				} else {
				
					// uncheck it
					$( 'input', this ).prop( 'checked', false );
				
				}
				
			});
			
			// when testing...
			//return false;
	
		}); // end form.submit()
		
		
		
		// prevent reset from submitting
		$( '.taxonomy-drilldown-reset' ).click( function( event ) {
			
			// override event
			event.preventDefault();
		
			// loop through all terms
			$( 'li.term-item label input' ).each( function(i) {
			
				// uncheck it
				$( this ).prop( 'checked', false );
				
			});
			
			// reset slider
			//console.log( 'reset to: ' + slider_first + ', ' + slider_last );
			$( "#slider-range" ).slider( 'values', 0, slider_first );
			$( "#slider-range" ).slider( 'values', 1, slider_last );
		
			// set range text
			$( "#cpncfr-range" ).val( slider_first + " - " + slider_last );
			
			// --<
			return false;
		
		});
		
	}
	
	

}); // end document ready





