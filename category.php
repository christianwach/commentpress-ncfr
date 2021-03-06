<?php get_header(); ?>



<!-- category.php (child) -->

<div id="wrapper">



<div id="main_wrapper" class="clearfix">



<div id="page_wrapper">



<?php

// until WordPress supports a locate_theme_file() function, use filter
$page_navigation = apply_filters( 
	'cp_template_page_navigation',
	get_template_directory() . '/assets/templates/page_navigation.php'
);

// always include
include( $page_navigation );

?>



<div id="content" class="clearfix">

<div class="post">



<?php if (have_posts()) : ?>

	<?php //$post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	<?php /* If this is a category archive */ if (is_category()) { ?>
	<h3 class="post_title">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h3>
	<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
	<h3 class="post_title">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h3>
	<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
	<h3 class="post_title">Archives</h3>
	<?php } ?>
	
	<?php while (have_posts()) : the_post(); ?>

		<div class="search_result">

			<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
			
			<?php
			
			// if we've elected to show the meta...
			if ( commentpress_get_post_meta_visibility( get_the_ID() ) ) {
		
			?>
			<div class="search_meta">
				
				<?php commentpress_echo_post_meta(); ?>
				
			</div>
			<?php
			
			}
		
			?>
			
			<?php the_excerpt() ?>
		
			<p class="search_meta"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
		
		</div><!-- /archive_item -->
	
	<?php endwhile; ?>


	
<?php else : ?>

	<h2 class="post_title">No Pages Found</h2>

	<?php get_search_form(); ?>
	
	<p class="no_topic_found">You can also: <a href="/browse-by-topic/" class="button">Browse by Topic</a></p>

<?php endif; ?>



</div><!-- /post -->

</div><!-- /content -->



<div class="page_nav_lower">
<?php

// include page_navigation again
include( $page_navigation );

?>
</div><!-- /page_nav_lower -->



</div><!-- /page_wrapper -->



</div><!-- /main_wrapper -->



</div><!-- /wrapper -->



<?php get_sidebar(); ?>



<?php get_footer(); ?>