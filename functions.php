<?php /*
===============================================================
Commentpress NCFR Child Theme Functions
===============================================================
AUTHOR: Christian Wach <needle@haystack.co.uk>
---------------------------------------------------------------
NOTES

Example theme amendments and overrides.

---------------------------------------------------------------
*/




/** 
 * @description: get IDs of NCFR special pages
 * @todo: 
 *
 */
function cpncfr_get_special_pages( $values = true ) {

	// LOCAL DEV SERVER
	if ( $_SERVER['HTTP_HOST'] == 'history.ncfr.cmw' ) {
		$pages = array(
			'browse_by_topic' => 1925,
			'directory' => 1899,
		);
	}

	// STAGING SERVER
	elseif ( $_SERVER['HTTP_HOST'] == 'h.haystack.co.uk' ) { 
		$pages = array(
			'browse_by_topic' => 1925,
			'directory' => 1899,
		);
	}
	
	// TODO: LIVE SERVER
	else {
		$pages = array(
			'browse_by_topic' => 1925,
			'directory' => 1899,
		);
	}
	
	
	
	// just the values?
	if ( $values ) { $pages = array_values( $pages ); }
	
	
	
	// --<
	return $pages;

}





/** 
 * @description: test for NCFR special page
 * @todo: 
 *
 */
function cpncfr_is_special_page() {

	// access post
	global $post;
	
	// kick out if no post object
	if ( !is_object( $post ) ) { return false; }
	
	// get IDs
	$ids = cpncfr_get_special_pages();
	
	// is it in the array?
	if ( in_array( $post->ID, $ids ) ) {
	
		// --<
		return $post->ID;
	
	}

	// --<
	return false;

}





/** 
 * @description: augment the CommentPress Default Theme setup function
 * @todo: 
 *
 */
function cpncfr_setup( 
	
) { //-->

	/** 
	 * Make theme available for translation.
	 * Translations can be added to the /languages/ directory of the child theme.
	 */
	load_theme_textdomain( 
	
		'cpncfr-theme', 
		get_stylesheet_directory() . '/languages' 
		
	);

	// use Featured Images (also known as post thumbnails)
	add_theme_support( 'post-thumbnails' );

}

// add after theme setup hook
add_action( 'after_setup_theme', 'cpncfr_setup' );






/** 
 * @description: override styles by enqueueing as late as we can
 * @todo:
 *
 */
function cpncfr_enqueue_styles() {

	// init
	$dev = '';
	
	// check for dev
	if ( defined( 'SCRIPT_DEBUG' ) AND SCRIPT_DEBUG === true ) {
		$dev = '.dev';
	}
	
	// dequeue parent theme colour styles
	wp_dequeue_style( 'cp_webfont_lato_css' );
	wp_dequeue_style( 'cp_colours_css' );
	
	// add child theme's css file
	wp_enqueue_style( 
	
		'cpncfr_css', 
		get_stylesheet_directory_uri() . '/assets/css/style-overrides'.$dev.'.css',
		array( 'cp_screen_css' ),
		'1.0', // version
		'all' // media
	
	);
	
	// add our theme javascript
	wp_enqueue_script(
	
		'cpncfr_js',
		get_stylesheet_directory_uri().'/assets/js/cpncfr_common.js',
		array( 'cp_common_js', 'jquery-ui-slider' )
	
	);
	
	// get vars
	$vars = cpncfr_get_javascript_vars();
	
	// localise with wp function
	wp_localize_script( 'cpncfr_js', 'CpncfrSettings', $vars );

	// add slider's css file
	wp_enqueue_style( 
	
		'cpncfr_slider_css', 
		get_stylesheet_directory_uri() . '/assets/css/smoothness/jquery-ui-1.10.2.custom.min.css',
		array( 'cpncfr_css' ),
		'1.0', // version
		'all' // media
	
	);
	
	/*
	----------------------------------------------------------------------------
	Only include the swipe script on mobile:
	it prevents text selection unless otherwise configured
	----------------------------------------------------------------------------
	*/
	
	/*
	// declare access to globals
	global $commentpress_core;
	
	// if we have the plugin enabled...
	if ( is_object( $commentpress_core ) ) {
	
		// test for mobile or tablet
		if ( $commentpress_core->is_mobile() OR $commentpress_core->is_tablet() ) {
	
			// add swipe javascript
			wp_enqueue_script(
			
				'cpncfr_swipe_js',
				get_stylesheet_directory_uri().'/assets/js/TouchSwipe-Jquery-Plugin/jquery.touchSwipe.min.js',
				array( 'cpncfr_js' )
			
			);
		
			// add our swipe activation javascript
			wp_enqueue_script(
			
				'cpncfr_swiper_js',
				get_stylesheet_directory_uri().'/assets/js/cpncfr_swiper.js',
				array( 'cpncfr_swipe_js' )
			
			);
			
		}
		
	}
	*/

}

// add a filter for the above
add_filter( 'wp_enqueue_scripts', 'cpncfr_enqueue_styles', 998 );






/** 
 * @description: localise our common javascript
 * @todo: 
 *
 */
function cpncfr_get_javascript_vars() {

	// init return
	$vars = array();
	
	

	// init vars
	$vars['cpncfr_page'] = 'cpncfr_default';



	// access post
	global $post;
	
	// kick out if no post object
	if ( !is_object( $post ) ) { return $vars; }
	


	// do we have an NCFR special page?
	$special = cpncfr_is_special_page();

	// if it's a special one...
	if ( is_numeric( $special ) ) {
	
		// find out which (by asking for 'not-just-values')
		$cpncfr_pages = cpncfr_get_special_pages( false );
		
		// loop through them
		foreach( $cpncfr_pages AS $cpncfr_item => $cpncfr_page ) {
		
			// if current page
			if ( $special == $cpncfr_page ) {
			
				// override var
				$vars['cpncfr_page'] = $cpncfr_item;
				
			}
			
		}
		
	
	}
	
	
	
	// --<
	return $vars;
	
}





/**
 * @description; adds our styles to the TinyMCE editor
 * @param string $mce_css The default TinyMCE stylesheets as set by WordPress
 * @return string $mce_css The list of stylesheets with ours added
 */
function cpncfr_add_tinymce_styles( $mce_css ) {

	// only on front-end
	if ( is_admin() ) { return $mce_css; }
	
	// add comma if not empty
	if ( !empty( $mce_css ) ) { $mce_css .= ','; }
	
	// add our editor styles
	$mce_css .= get_stylesheet_directory_uri().'/assets/css/cpncfr-comment-form.css';
	
	return $mce_css;
	
}

// add filter for the above
add_filter( 'mce_css', 'cpncfr_add_tinymce_styles' );





/**
 * Retrieve the avatar for a user who provided a user ID or email address.
 * CMW: Copied from core - removed show gravatars option check because only called by
 * author.php template
 *
 * @since 2.5
 * @param int|string|object $id_or_email A user ID,  email address, or comment object
 * @param int $size Size of the avatar image
 * @param string $default URL to a default image to use if no avatar is available
 * @param string $alt Alternative text to use in image tag. Defaults to blank
 * @return string <img> tag for the user's avatar
*/
function cpncfr_get_avatar( $id_or_email, $size = '96', $default = '', $alt = false ) {

	if ( false === $alt)
		$safe_alt = '';
	else
		$safe_alt = esc_attr( $alt );

	if ( !is_numeric($size) )
		$size = '96';

	$email = '';
	if ( is_numeric($id_or_email) ) {
		$id = (int) $id_or_email;
		$user = get_userdata($id);
		if ( $user )
			$email = $user->user_email;
	} elseif ( is_object($id_or_email) ) {
		// No avatar for pingbacks or trackbacks
		$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
		if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
			return false;

		if ( !empty($id_or_email->user_id) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_userdata($id);
			if ( $user)
				$email = $user->user_email;
		} elseif ( !empty($id_or_email->comment_author_email) ) {
			$email = $id_or_email->comment_author_email;
		}
	} else {
		$email = $id_or_email;
	}

	if ( empty($default) ) {
		$avatar_default = get_option('avatar_default');
		if ( empty($avatar_default) )
			$default = 'mystery';
		else
			$default = $avatar_default;
	}

	if ( !empty($email) )
		$email_hash = md5( strtolower( trim( $email ) ) );

	if ( is_ssl() ) {
		$host = 'https://secure.gravatar.com';
	} else {
		if ( !empty($email) )
			$host = sprintf( "http://%d.gravatar.com", ( hexdec( $email_hash[0] ) % 2 ) );
		else
			$host = 'http://0.gravatar.com';
	}

	if ( 'mystery' == $default )
		$default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$size}"; // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
	elseif ( 'blank' == $default )
		$default = $email ? 'blank' : includes_url( 'images/blank.gif' );
	elseif ( !empty($email) && 'gravatar_default' == $default )
		$default = '';
	elseif ( 'gravatar_default' == $default )
		$default = "$host/avatar/?s={$size}";
	elseif ( empty($email) )
		$default = "$host/avatar/?d=$default&amp;s={$size}";
	elseif ( strpos($default, 'http://') === 0 )
		$default = add_query_arg( 's', $size, $default );

	if ( !empty($email) ) {
		$out = "$host/avatar/";
		$out .= $email_hash;
		$out .= '?s='.$size;
		$out .= '&amp;d=' . urlencode( $default );

		$rating = get_option('avatar_rating');
		if ( !empty( $rating ) )
			$out .= "&amp;r={$rating}";

		$avatar = "<img alt='{$safe_alt}' src='{$out}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
	} else {
		$avatar = "<img alt='{$safe_alt}' src='{$default}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
	}

	return apply_filters('get_avatar', $avatar, $id_or_email, $size, $default, $alt);
}






/** 
 * @description: override default setting for comment registration
 * @todo: 
 *
 */
function cpncfr_sidebar_tab_order( $order ) {
	
	// ignore what's sent to us and set our own order here
	$order = array( 'comments', 'activity', 'contents' );
	
	// --<
	return $order;

}

// uncomment the line below to enable the order defined above
//add_filter( 'cp_sidebar_tab_order', 'cpncfr_sidebar_tab_order', 21, 1 );





/** 
 * @description: override display of page numbers by intercepting value
 * @todo: 
 *
 */
function cpncfr_remove_page_numbers( $page_num ) {
	
	// --<
	return '';

}

// add a filter for the above
add_filter( 'cp_nav_page_num', 'cpncfr_remove_page_numbers', 10, 1 );





/** 
 * @description: override template by intercepting value
 * @todo: 
 *
 */
function cpncfr_template_toc_sidebar() {
	
	// --<
	return get_stylesheet_directory() . '/assets/templates/toc_sidebar.php';

}

// add a filter for the above
add_filter(	'cp_template_toc_sidebar', 'cpncfr_template_toc_sidebar' );





/** 
 * @description: override title by intercepting value
 * @todo: 
 *
 */
function cpncfr_author_page_pages_list_title() {
	
	// --<
	return __( 'Pages featuring', 'cpncfr-theme' );

}

// add a filter for the above
add_filter(	'cp_author_page_pages_list_title', 'cpncfr_author_page_pages_list_title' );






/** 
 * @description: override title by intercepting value
 * @todo: 
 *
 */
function cpncfr_wp_head() {
	
	// --<
	echo '<link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/assets/images/favicon.ico" type="image/x-icon" />';

}

// add a filter for the above
add_action( 'wp_head', 'cpncfr_wp_head' );





/** 
 * @description: add title by intercepting value
 * @todo: 
 *
 */
function cpncfr_gallery_style( $existing ) {
	
	// prepend our gallery title
	return '<h3>Gallery</h3>' . "\n\t\t" . $existing;
	
}

// add a filter for the above
add_filter( 'gallery_style', 'cpncfr_gallery_style', 10, 1 );





/** 
 * @description: since it seems to point to homepage, update the QMT reset url by intercepting value
 * @todo: 
 *
 */
function cpncfr_qmt_base_url( $existing ) {

	global $post;
	
	// prepend our gallery title
	return get_permalink( $post->ID );
	
}

// add a filter for the above
//add_filter( 'qmt_base_url', 'cpncfr_qmt_base_url', 10, 1 );






/** 
 * @description: show user(s) in the loop
 * @todo: 
 *
 */
function cpncfr_get_authors_as_tags() {

	// compat with Co-Authors Plus
	if ( function_exists( 'get_coauthors' ) ) {
	
		// get multiple authors
		$authors = get_coauthors();
		//print_r( $authors ); die();
		
		// if we get some
		if ( !empty( $authors ) ) {
		
			// use the Co-Authors format of "name, name, name & name"
			$author_html = '';
			
			// init counter
			$n = 1;
			
			// find out how many author we have
			$author_count = count( $authors );
		
			// loop
			foreach( $authors AS $author ) {
				
				// default to comma
				$sep = ', ';
				
				// if we're on the last, don't add
				if ( $n == $author_count ) { $sep = ''; }
				
				// get name
				$author_html .= commentpress_echo_post_author( $author->ID, false );
				
				// and separator
				$author_html .= $sep;
				
				// increment
				$n++;
				
			}
			
			// return it
			return __( 'People: ', 'cpncfr-theme' ).$author_html;
				
		}
	
	}
	
	// --<
	return '';
		
}






/** 
 * @description: show feature image and optional caption
 * @todo: 
 *
 */
function cpncfr_get_feature_image() {

	// do we have a featured image?
	if ( has_post_thumbnail() ) {
	
		?>
		<div class="ncfr_feature_image">
		<?php
		
		// show it
		echo get_the_post_thumbnail( get_the_ID(), 'medium' );
		
		// get attachment ID
		$attachment_id = get_post_thumbnail_id();
		
		// set args to get attachment post data
		$args = array(
			
			'p' => $attachment_id, 
			'post_type' => 'attachment'
			
		);
		
		// get posts for this attachment (should only be one)
		$attachment_posts = get_posts( $args );
		//print_r( $attachment_posts ); die();
		
		// did we get one?
		if ( count( $attachment_posts ) > 0 ) {
		
			// use the first as metadata
			$metadata = $attachment_posts[0];
			//print_r( $metadata ); die();
		
			// do we have a caption?
			if ( isset( $metadata->post_excerpt ) AND $metadata->post_excerpt != '' ) {
			
				// show caption
				?>
				<p><?php echo $metadata->post_excerpt; ?></p>
				<?php
			
			}
			
		}
	
		?>
		</div>
		<?php
		
	}
	
}





/** 
 * @description: show feature image and optional caption
 * @todo: 
 *
 */
function cpncfr_text_highlight( $text ) {

	// is this a search result?
	if( is_search() AND in_the_loop() ) {
		
		//we need this for the search terms
		global $wp_query;
		
		// get search terms
		$keys = explode( ' ', $wp_query->query_vars['s'] );
		
		// escape each entry
		array_walk( $keys, create_function('&$val', '$val = preg_quote(trim($val));')); 
		//print_r( $keys );
		
		// convert for use in regex
		$regex = '/('.implode( '|', $keys ) .')/iu';
		//print_r( $regex );
		
		// define substitution
		$substitution = '<span class="search_highlight">\0</span>';
		
		// perform replacement
		$text = preg_replace(
		
			$regex,
			$substitution,
			$text
			
		);
		
	}

	return $text;
}

// add filters for the above
add_filter( 'the_title', 'cpncfr_text_highlight' );
add_filter( 'the_excerpt', 'cpncfr_text_highlight' );






/** 
 * @description: filter search query to order by menu_order
 * @todo: 
 *
 */
function cpncfr_search_filter( &$query ) {
	
	// restrict to search outside admin
	if ( ! is_admin() AND $query->is_search ) {
		
		// restrict to pages
		$query->set( 'post_type', 'page' );
		
		// order by ascending menu_order
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
		
	}
	
}

// add filter for the above
add_filter( 'pre_get_posts','cpncfr_search_filter' );





